<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\ActivityRepository;


class ActivityService
{
    private $doctrine;
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $entityManager;
    }
    
    
    public function deleteActivity(ManagerRegistry $doctrine, UserInterface $user, $id): Activity
{
    $activity_repo = $doctrine->getManager()->getRepository(Activity::class);
    $activity = $activity_repo->find($id);

    if ($activity && $activity->getIdUser() === $user) {
        $activity_manager = $doctrine->getManager();
        $activity_manager->remove($activity);
        $activity_manager->flush();

        return $activity;
    }

    throw new NotFoundHttpException('Activity not found or cannot be deleted.');
}


    public function getActivityReviews($idActivity) {
        $activity_repo = $this->doctrine->getRepository(Activity::class);
        $activity = $activity_repo->find($idActivity);

        $conn = $this->entityManager->getConnection();

        $sql = '
        SELECT ua.score, ua.review, u.user_name, u.user_lastname
        FROM user_activity ua
        INNER JOIN user u ON ua.user_id = u.id_user
        WHERE ua.activity_id = :idActivity';

        $resultSet = $conn->executeQuery($sql, ['idActivity' => $idActivity])->fetchAll();

        $scores = [];
        $reviews = [];
        $userName = [];
        $userLastname = [];

        foreach ($resultSet as $row) {
            $scores[] = $row['score'];
            $reviews[] = $row['review'];
            $userName[] = $row['user_name'];
            $userLastname[] = $row['user_lastname'];
        }

        return [
            'scores' => $scores,
            'reviews' => $reviews,
            'username' => $userName,
            'userlastname' => $userLastname
        ];
    }
}