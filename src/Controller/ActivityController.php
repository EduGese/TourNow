<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\CreateActivityFormType;

use Symfony\Component\Security\Core\Security;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\ActivityReviewService;





use App\Entity\Activity;


//FUNCIONES A CREAR EN ESTE CONTROLADOR:
//Voy a usar este controlador para:
//Crear actividad nueva  SEMIHECHO
//Editar una actividad creada
//Eliminar una actividad creada   HECHO


class ActivityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteActivity(ManagerRegistry $doctrine, UserInterface $user, Request $request): response
    {

        $idActivity = $request->attributes->get('id');
        $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);
        $activity_manager = $doctrine->getManager();
        $activity_manager->remove($activity);
        $activity_manager->flush();

        return $this->redirect(
            $this->generateUrl('show_admin_activities')
        );
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
    public function showActivityReviews(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, $id, ActivityReviewService $activityReviewService): response
    {


        $activityReviews = $activityReviewService->getActivityReviews($id);

        return $this->render('showActivityReviews.html.twig', $activityReviews);
        // $idActivity = $request->attributes->get('id');
        // $activity_repo = $doctrine->getRepository(Activity::class);
        // $activity = $activity_repo->find($idActivity);

        // $conn = $this->entityManager->getConnection();

        // $sql = '
        // SELECT ua.score, ua.review, u.user_name, u.user_lastname
        // FROM user_activity ua
        // INNER JOIN user u ON ua.user_id = u.id_user
        // WHERE ua.activity_id = :idActivity';

        // $resultSet = $conn->executeQuery($sql, ['idActivity' => $idActivity])->fetchAll();

        // $scores = [];
        // $reviews = [];
        // $userName = [];
        // $userLastname = [];

        // foreach ($resultSet as $row) {
        //     $scores[] = $row['score'];
        //     $reviews[] = $row['review'];
        //     $userName[] = $row['user_name'];
        //     $userLastname[] = $row['user_lastname'];
        // }

        // return $this->render('showActivityReviews.html.twig', [
        //     'scores' => $scores,
        //     'reviews' => $reviews,
        //     'username' => $userName,
        //     'userlastname' => $userLastname
        // ]);
    }
}
