<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\CreateActivityFormType;
use App\Entity\User;

use Symfony\Component\Security\Core\Security;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\ActivityService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;






use App\Entity\Activity;


//FUNCIONES A CREAR EN ESTE CONTROLADOR:
//Voy a usar este controlador para:
//Crear actividad nueva  SEMIHECHO
//Editar una actividad creada
//Eliminar una actividad creada   HECHO


class ActivityController extends AbstractController
{
    private $entityManager;
    private $doctrine;
    private $mailer;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
        $this->logger = $logger;

        $this->mailer = $mailer;
    }

    public function deleteActivity(Request $request, ActivityService $activityService, ManagerRegistry $doctrine, UserInterface $user, $id): Response
    {
        try {
            $id = $request->attributes->get('id');
            $activityService->deleteActivity($doctrine, $user, $id, $request);

            // Mostrar mensaje de éxito
            $this->addFlash('delete', 'Actividad eliminada con éxito, se han mandado email a los usuarios para advertirles ');
        } catch (NotFoundHttpException $exception) {
            // Mostrar mensaje de error
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('show_admin_activities');
    }
    public function createActivity(Request $request, Security $security): response
    {

        $activity = new Activity();
        $form = $this->createForm(CreateActivityFormType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Establecer el ID del usuario actualmente autenticado
            $user = $security->getUser();
            $activity->setIdUser($user);

            // Procesar la imagen
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                // Mover el archivo a la carpeta src/Img
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/images',
                    $newFilename
                );

                // Guardar la ruta en la entidad Activity
                $activity->setImage('images/' . $newFilename);
            }

            // Comprobamos la fecha introducida
            $fechaIntroducida = $form->get('date')->getData();
            $fechaActual = new \DateTime();

            $activityRepository = $this->entityManager->getRepository(Activity::class);
            $activityNames = $activityRepository->findAllActivityNames();

            $activityName = $form->get('activity_name')->getData();

            if (in_array($activityName, $activityNames)) {
                $this->addFlash('nombre_duplicado', 'El nombre de la actividad ya existe, elija otro diferente');
                return $this->render('adminCreateActivity.html.twig', [
                    'form' => $form->createView(),
                ]);
            }




            if ($fechaIntroducida < $fechaActual) {
                // Fecha introducida es anterior a la actual
                $this->addFlash('fecha_erronea', 'La fecha introducida no puede ser anterior a la fecha actual.');
                return $this->render('adminCreateActivity.html.twig', [
                    'form' => $form->createView(),
                ]);
            }



            // Guardar la actividad en la base de datos
            $this->entityManager->persist($activity);
            $this->entityManager->flush();

            // Redirigir a alguna página de éxito o realizar otras acciones
            $this->addFlash('create', '¡Actividad creada con éxito!');
            return $this->redirectToRoute('show_admin_activities');
        }

        return $this->render('adminCreateActivity.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function editActivity(Request $request, EntityManagerInterface $entityManager, $id, Security $security, ManagerRegistry $doctrine): Response
    {

        // Obtén la actividad desde la base de datos
        $activity = $entityManager->getRepository(Activity::class)->find($id);
        // Crea el formulario de edición utilizando el formulario de creación reutilizado
        $form = $this->createForm(CreateActivityFormType::class, $activity);
        $form->handleRequest($request);

        // Maneja la solicitud de edición
        if ($form->isSubmitted() && $form->isValid()) {
            // Valida los datos del formulario y aplica los cambios a la actividad
            
            // Comprobamos la fecha introducida
            $fechaIntroducida = $form->get('date')->getData();
            $fechaActual = new \DateTime();

           

            if ($fechaIntroducida < $fechaActual) {
                // Fecha introducida es anterior a la actual
                $this->addFlash('fecha_erronea', 'La fecha introducida no puede ser anterior a la fecha actual.');
                return $this->render('adminCreateActivity.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            // Guarda los cambios en la base de datos
            $entityManager->flush();



            //flash message

            $this->addFlash('edit', '¡Actividad editada con éxito!, se han mandado email a los usuarios para advertirles ');

            //envio de mails//


            //Obtener mails de usuarios registrados en la actividad
            $activityId = $activity->getIdActivity();
            $conn = $entityManager->getConnection();


            $query = "SELECT u.email FROM user_activity ua
            INNER JOIN user u ON ua.user_id = u.id_user
            WHERE ua.activity_id = :activity_id";

            $params = [
                'activity_id' => $activityId, // ID de la actividad deseada
            ];
            // Ejecutar la consulta
            $statement = $conn->executeQuery($query, $params);

            // Obtener los correos electrónicos
            $emails = $statement->fetchAllAssociative();

            $emailList = [];
            foreach ($emails as $row) {
                $emailList[] = $row['email'];
            }

            //Mandar mails
            $user = $security->getUser();
            $user_repo = $doctrine->getRepository(User::class);
            $user_mail = $user_repo->find($user)->getEmail(); //mail del admin 

            $email = (new Email())
                ->from($user_mail)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Una actividad a la que te has unido ha cambiado')
                ->text('Una actividad a la que te has unido ha cambiado, revisa tu cuenta');

            foreach ($emailList as $recipientEmail) {
                $email->to($recipientEmail);
                $this->mailer->send($email);
            }




            return $this->render('user/showActivity.html.twig', [
                'activity' => $activity,
                'id' => $activity->getIdActivity(),
                'emails' => $emails,
            ]);
        }


        return $this->render('edit_activity.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity,
        ]);
    }

    public function showActivityReviews($id, ActivityService $activityService): response
    {


        $activityReviews = $activityService->getActivityReviews($id);

        return $this->render('showActivityReviews.html.twig', $activityReviews);
    }
    public function printActivityReviewForm(Request $request, ManagerRegistry $doctrine): response
    {




        $idActivity = $request->attributes->get('id');
        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);

        return $this->render('activityReviewForm.html.twig', [
            'activity' => $activity
        ]);
    }
    public function addActivityReview(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, Security $security): response
    {

        $idActivity = $request->attributes->get('id'); //idactivity
        $scores = $request->attributes->get('scores'); //Cantidad de puntuaciones de la actividad
        $average_score = $request->attributes->get('average_score'); //Media de puntuacion de la actividad

        $score = $request->request->get('score'); //Puntuacion que da el usuario, se recibe desde el formulario
        $review = $request->request->get('review'); //Review que da el usuario, se recibe desde el formulario



        $user = $security->getUser();
        $user_repo = $doctrine->getRepository(User::class);
        $user_id = $user_repo->find($user)->getId(); //id del usuario 

        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity); //Actividad que estamos manejando

        // Insertar o actualizar en la tabla user_activity
        $conn = $entityManager->getConnection();
        $query = 'INSERT INTO user_activity (user_id, activity_id, review, score)
              VALUES (:user_id, :activity_id, :review, :score)
              ON DUPLICATE KEY UPDATE review = VALUES(review), score = VALUES(score)';
        $params = [
            'user_id' => $user_id,
            'activity_id' => $idActivity,
            'review' => $review,
            'score' => $score
        ];
        $conn->executeStatement($query, $params);
        ///////Logica para añadir puntuacion media a tabla activity

        if ($scores == 0) {
            $activity->setScores(1);
            $activity->setAverageScore($score);
            $activity->addScore($score);
            $entityManager->flush();
        }
        // $scoreCounting = $activity->getScores();
        // $activity->setScores($scoreCounting+1);
        // $scoreList = $activity->getScoreList();
        // $totalScore = array_sum($scoreList);
        // $newAverageScore = $totalScore/$scores;///ERROR -->Division by zero
        // $activity->setAverageScore($newAverageScore);





        ///Esto sobra:
        $idActivity = $request->attributes->get('id');
        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);
        ///
        return $this->render('activityReviewForm.html.twig', [
            'activity' => $activity,
            'scores' => $scores,
            'average_score' => $average_score


        ]);
    }
}
