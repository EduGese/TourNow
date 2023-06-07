<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;

class ActivityReviewService
{
    private $doctrine;
    private $entityManager;

    public function __construct(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $entityManager;
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