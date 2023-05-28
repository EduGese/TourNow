<?php 
// src/Controller/Usercontroller.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Activity;



class UserController extends AbstractController
{
    
    public function userDashboard( ManagerRegistry $doctrine): Response
    {
        $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
        $activities = $activity_repo->findAll();
        return $this->render('user/userDashboard.html.twig',[
            'activities'=>$activities,
        ]);
    }
}