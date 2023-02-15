<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//         if ($this->getUser()) {
//             return $this->redirectToRoute('target_path');
//         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, RegisterService $registerService): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();
            $registerService->register($user, $avatar);
//            return $this->render('security/register.html.twig', [
//                'isEmailConfirmationPending' => true
//            ]);
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/login-check', name: 'app_loginCheck')]
    public function checkLogin(Request $request, RegisterService $registerService, EntityManagerInterface $em): Response | null
    {
        $user = $registerService->checkUserToken($request->get('token'));

        if(!$user) {
            throw new \Exception('Invalid token');
        }
        $user->setRoles(['IS_FULLY_AUTHENTICATED', ...$user->getRoles()]);
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_login');
    }

}
