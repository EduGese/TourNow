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

    public function deleteActivity(ManagerRegistry $doctrine, UserInterface $user, Request $request): response{
        
        $idActivity = $request->attributes->get('id');
        $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);
        $activity_manager = $doctrine->getManager();
        $activity_manager->remove($activity);
        $activity_manager->flush();

        return $this->redirect(
            $this->generateUrl('admin_dashboard')
        );
    }
    public function createActivity( Request $request, Security $security): response{
        
        $activity = new Activity();
        $form = $this->createForm(CreateActivityFormType::class, $activity);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Establecer el ID del usuario actualmente autenticado
            $user = $security->getUser();
            $activity->setIdUser($user);
            // Guardar la actividad en la base de datos
            $this->entityManager->persist($activity);
            $this->entityManager->flush();
    
            // Redirigir a alguna página de éxito o realizar otras acciones
            return $this->redirectToRoute('admin_dashboard');
        }
    
        return $this->render('adminCreateActivity.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
