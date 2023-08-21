<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


class ActivityService extends AbstractController
{
    private $doctrine;
    private $entityManager;
    private $mailer;

    public function __construct( MailerInterface $mailer, ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {
        $this->doctrine = $doctrine;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
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
    public function uploadImg($form, $activity){
        // Procesa la imagen
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $newFilename = uniqid() . '.' . $imageFile->getClientOriginalExtension();

            // Mueve el archivo a la carpeta src/Img
            $imageFile->move(
                $this->getParameter('kernel.project_dir') . '/public/images',
                $newFilename
            );

            // Guarda la ruta en la entidad Activity
            $activity->setImage('images/' . $newFilename);
        }
    }
    public function checkDate($form){
            $fechaIntroducida = $form->get('date')->getData();
            $fechaActual = new \DateTime();

            if ($fechaIntroducida < $fechaActual) {
                return false;
            }else{
                return true;
            }
    }
    public function sendMails($entityManager, $activity, Security $security, ManagerRegistry $doctrine){
        //Obtener mails de usuarios registrados en la actividad
        $activityId = $activity->getIdActivity();
        $conn = $entityManager->getConnection();


            $query = "SELECT u.email FROM user_activity ua
                                INNER JOIN user u ON ua.user_id = u.id_user
                                WHERE ua.activity_id = :activity_id";

            $params = [
                'activity_id' => $activityId,
            ];

            $statement = $conn->executeQuery($query, $params);

            // Obtener los correos electrónicos
            $emails = $statement->fetchAllAssociative();

            $emailList = [];
            foreach ($emails as $row) {
                $emailList[] = $row['email'];
            }

            //Mandar mails
            $user = $security->getUser();
            $user_repo = $doctrine->getRepository(User::class);
            $user_mail = $user_repo->find($user)->getEmail();

            $email = (new Email())
                ->from($user_mail)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Una actividad a la que te has unido ha cambiado')
                ->text('Una actividad a la que te has unido ha cambiado, revisa tu cuenta');

            foreach ($emailList as $recipientEmail) {
                $email->to($recipientEmail);
                $this->mailer->send($email);
            }
    }
}