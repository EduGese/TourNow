<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;




use App\Entity\Activity;


//FUNCIONES A CREAR EN ESTE CONTROLADOR:
         //Voy a usar este controlador para:
         //Crear actividad nueva
         //Editar una actividad creada
         //Eliminar una actividad creada   HECHO


class ActivityController extends AbstractController
{
   

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
}
