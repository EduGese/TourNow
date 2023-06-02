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
    
    public function userDashboard( ManagerRegistry $doctrine, Security $security): Response
    {
        $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
        $allActivities = $activity_repo->findAll();

         
        $userActivities = $security->getUser()->getCustomerActivities();
        // $userActivities = $activity_repo->findBy($userId);
        // var_dump($userId);
        // return new Response();

        




        return $this->render('user/userDashboard.html.twig',[
            'allActivities' => $allActivities,
            'userActivities' =>  $userActivities,
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

}