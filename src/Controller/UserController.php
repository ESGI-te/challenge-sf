<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user',name: 'user_')]
class UserController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/profile', name: 'profile_private', methods: ['GET'])]
    public function show(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Security $security,Request $request,UserService $userService): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();
            $userService->update($user, $avatar);

            return $this->redirectToRoute('user_profile_private', [
                'id' => $user->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/recipes', name: 'recipes', methods: ['GET'])]
    public function recipes(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
        return $this->render('user/recipes.html.twig', [
            'user' => $user,
            'recipes' => $user->getRecipes()
        ]);
    }

    #[Route('/{id}', name: 'profile_public', methods: ['GET'])]
    public function public_profile(User $user): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'recipes' => $user->getRecipes()
        ]);
    }

}
