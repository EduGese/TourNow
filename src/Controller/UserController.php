<?php
// src/Controller/Usercontroller.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Activity;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;



class UserController extends AbstractController
{

    public function userDashboard(): Response
    {

        return $this->render('user/userDashboard.html.twig');
    }

    public function userActivities(Security $security): Response
    {

        $userActivities = $security->getUser()->getCustomerActivities();

        return $this->render('user/userActivityList.html.twig', [
            'userActivities' =>  $userActivities,
        ]);
    }

    public function allActivities(ManagerRegistry $doctrine): Response
    {
        $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
        $allActivities = $activity_repo->findAll();

        return $this->render('user/allActivitiesList.html.twig', [
            'allActivities' => $allActivities,
        ]);
    }
    public function showActivity(Request $request, ManagerRegistry $doctrine): Response
    {
        $idActivity = $request->attributes->get('id');
        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);

        return $this->render('user/showActivity.html.twig', [
            'activity' => $activity
        ]);
    }


    public function showJoinedActivity(Request $request, ManagerRegistry $doctrine): Response
    {
        $idActivity = $request->attributes->get('id');
        $activity_repo = $doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);

        return $this->render('user/showJoinedActivity.html.twig', [
            'activity' => $activity
        ]);
    }
    public function joinActivity(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, Security $security, $idActivity, $nameActivity): Response
    { {
            $option = $request->request->get('tickets'); //numero de tickets seleccionados por el usuario
            $idActivity = $request->attributes->get('idActivity'); //id de la actividad 
            $tickets_number = $request->attributes->get('tickets_number'); //Cantidad de tickets que dispone la actividad
            $nameActivity = $request->attributes->get('nameActivity');

            $user = $security->getUser();
            $user_repo = $doctrine->getRepository(User::class);
            $user_id = $user_repo->find($user)->getId(); //id del usuario 


            // Obtener el objeto User y Activity
            $user = $entityManager->getRepository(User::class)->find($user_id);
            $activity = $entityManager->getRepository(Activity::class)->find($idActivity);

            // Agregar la relación
            $user->addCustomerActivity($activity);
            $activity->addCustomerUser($user);

            // Descontar los tickets disponibles de la actividad 

            $tickets_number_final = $tickets_number - $option;
            if ($tickets_number_final < 0) {
                // $idActivity = $request->attributes->get('id');
                $activity_repo = $doctrine->getRepository(Activity::class);
                $activity = $activity_repo->find($idActivity);
                $this->addFlash(
                    'full',
                    'La actividad: ' . $nameActivity .  ' tiene menos plazas disponibles de las que estas intentando comprar  '
                );
                $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
                $allActivities = $activity_repo->findAll();

                return $this->render('user/allActivitiesList.html.twig', [
                    'allActivities' => $allActivities,
                ]);
            }

            $activity->setTickets($tickets_number_final);

            // Persistir los cambios en la base de datos
            $entityManager->flush();
            $this->addFlash(
                'joined',
                'Te has unido a la actividad:  ' . $nameActivity
            );



            $userActivities = $security->getUser()->getCustomerActivities();

            return $this->render('user/userActivityList.html.twig', [
                'userActivities' =>  $userActivities,
            ]);

        }
    }
}
