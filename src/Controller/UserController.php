<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user',name: 'user_')]
class UserController extends AbstractController
{
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

    #[Route('/editAdmin/{id}', name: 'profile_edit_admin', methods: ['GET', 'POST']), IsGranted("ROLE_ADMIN")]
    public function editAdmin(Request $request, User $user, UserRepository $userRepository): Response
    {

        $form = $this->createForm(UserEditType::class, $user, [
            'admin_roles' => $user->getRoles(),
            'user_plan' => $user->getPlan(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/editAdmin.html.twig', [
            'user' => $user,
            'form' => $form,
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

            $userService->update($user);

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
