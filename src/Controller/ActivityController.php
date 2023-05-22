<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\User;
use App\Entity\Activity;


//FUNCIONES A CREAR EN ESTE CONTROLADOR:
         //Voy a usar este controlador para:
         //Crear actividad nueva
         //Editar una actividad creada
         //Eliminar una actividad creada


class ActivityController extends AbstractController
{
    #[Route('/activity', name: 'app_activity')]
    public function index(): Response
    {
        return $this->render('activity/index.html.twig', [
            'controller_name' => 'ActivityController',
        ]);
    }

    // public function activityListByUser(ManagerRegistry $doctrine, UserInterface $user): response{
    // //    $user = new User();
    //     $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
    //     $qb =  $activity_repo->createQueryBuilder('a')
    //                         ->Where("a.id_user = :id_user")
    //                         ->setParameter('id_user', $user->getId())
    //                         ->orderBy('a.price', 'ASC')
    //                         ->getQuery();
    //     $resulSet = $qb->execute();
    //     return $this->render('admin/adminActivitiesAll.html.twig',[
    //         'activities' => $resulSet
    //     ]);
    // }
}
