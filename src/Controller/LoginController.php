<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use LoginData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $loginData = new LoginData();

        $form = $this->createForm(LoginType::class, $loginData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var LoginData */
            $loginData = $form->getData();
            return $this->redirectToRoute('app_home_showissues');
        }
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
        ]);
    }
}
