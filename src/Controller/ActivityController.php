<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
use Psr\Log\LoggerInterface;
use App\Entity\Activity;
use Symfony\Component\HttpFoundation\RequestStack;



class ActivityController extends AbstractController
{
    private $entityManager;
    private $doctrine;
    private $mailer;
    private $logger;

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger, private RequestStack $requestStack)
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

            $this->addFlash('delete', 'Actividad eliminada con éxito, se han mandado email a los usuarios para advertirles ');
        } catch (NotFoundHttpException $exception) {
            // Mostrar mensaje de error
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('show_admin_activities');
    }
    public function createActivity(Request $request, Security $security, ActivityService $activityService): response
    {

        $activity = new Activity();
        $form = $this->createForm(CreateActivityFormType::class, $activity);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Establece el ID del usuario actualmente autenticado
            $user = $security->getUser();
            $activity->setIdUser($user);

            // Procesa la imagen
            $activityService->uploadImg($form, $activity);


            // Comprobamos la fecha introducida
            if (!$activityService->checkDate($form)) {
                $this->addFlash('fecha_erronea', 'La fecha introducida no puede ser anterior a la fecha actual.');
                return $this->render('adminCreateActivity.html.twig', [
                    'form' => $form->createView(),
                ]);
            }else{
                $this->entityManager->persist($activity);
            $this->entityManager->flush();
            $this->addFlash('create', '¡Actividad creada con éxito!');
            return $this->redirectToRoute('show_admin_activities');
            }
        }

        return $this->render('adminCreateActivity.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function editActivity(Request $request, EntityManagerInterface $entityManager, $id, Security $security, ManagerRegistry $doctrine): Response
    {


        $activity = $entityManager->getRepository(Activity::class)->find($id);

        $form = $this->createForm(CreateActivityFormType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Comprobamos la fecha introducida
            $fechaIntroducida = $form->get('date')->getData();
            $fechaActual = new \DateTime();

            if ($fechaIntroducida < $fechaActual) {
                $this->addFlash('fecha_erronea', 'La fecha introducida no puede ser anterior a la fecha actual.');
                $formData = $request->request->all();
                return $this->render('adminCreateActivity.html.twig', [
                    'form' => $form->createView(),
                    'form_data' => $formData,
                ]);
            }
            $this->entityManager->persist($activity);
            $this->entityManager->flush();
            $this->addFlash('edit', '¡Actividad editada con éxito!, se han mandado email a los usuarios para advertirles ');

            //Envio de mails//


            //Obtener mails de usuarios registrados en la actividad
            $activityId = $activity->getIdActivity();
            $conn = $entityManager->getConnection();


            $query = "SELECT u.email FROM user_activity ua
                                INNER JOIN user u ON ua.user_id = u.id_user
                                WHERE ua.activity_id = :activity_id";

            $params = [
                'activity_id' => $activityId,
            ];

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
            $user_mail = $user_repo->find($user)->getEmail();

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

        $idActivity = $request->attributes->get('id');


        $score = $request->request->get('score');
        $review = $request->request->get('review');



        $user = $security->getUser();
        $user_repo = $doctrine->getRepository(User::class);
        $user_id = $user_repo->find($user)->getId();

        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);

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
        return $this->render('user/showActivity.html.twig', [
            'activity' => $activity
        ]);
    }
}
