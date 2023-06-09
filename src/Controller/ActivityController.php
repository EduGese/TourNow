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

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
    }

    public function deleteActivity(Request $request, ActivityService $activityService, ManagerRegistry $doctrine, UserInterface $user, $id): Response
    {
        try {
            $id = $request->attributes->get('id');
            $activityService->deleteActivity($doctrine, $user, $id, $request);

            // Mostrar mensaje de éxito
            $this->addFlash('success', sprintf('Successfully deleted activity with ID %d.', $id));
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



            // Guardar la actividad en la base de datos
            $this->entityManager->persist($activity);
            $this->entityManager->flush();

            // Redirigir a alguna página de éxito o realizar otras acciones
            return $this->redirectToRoute('show_admin_activities');
        }

        return $this->render('adminCreateActivity.html.twig', [
            'form' => $form->createView(),
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
        $scores = $request->attributes->get('scores');//Cantidad de puntuaciones de la actividad
        $average_score = $request->attributes->get('average_score');//Media de puntuacion de la actividad
        
        $score = $request->request->get('score');//Puntuacion que da el usuario, se recibe desde el formulario
        $review = $request->request->get('review');//Review que da el usuario, se recibe desde el formulario
        
        

        $user = $security->getUser();
        $user_repo = $doctrine->getRepository(User::class);
        $user_id = $user_repo->find($user)->getId(); //id del usuario 

        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);//Actividad que estamos manejando

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

        if ($scores==0) {
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
