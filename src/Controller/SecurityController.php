<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
  
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        if ($security->getUser()) {
            $roles = $security->getUser()->getRoles();
            $url = in_array('ROLE_ADMIN', $roles) ? 'admin_dashboard' : 'user_dashboard';
            return $this->redirectToRoute($url);
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
