<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Twig\Environment;

class RegisterService
{
    public function __construct(UserPasswordHasherInterface $passwordHasher, MailService $mailService, EntityManager $em, Environment $twig, UserRepository $userRepository) {
        $this->passwordHasher = $passwordHasher;
        $this->mailService = $mailService;
        $this->em = $em;
        $this->twig = $twig;
        $this->userRepository = $userRepository;
    }
    public function register(User $user): void {
        $token = Uuid::uuid4();
        $date = new DateTimeImmutable('now');
        $hash = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setCreatedAt($date);
        $user->setToken($token);
        $this->em->persist($user);
        $this->em->flush();
        $this->sendEmailConfirmation($user, $token);
    }

    private function sendEmailConfirmation(User $user, string $token): void {
        $emailTemplate = $this->twig->render('emails/accountVerification.html.twig', [
            'token' =>  $token,
            'firstname' => $user->getFirstname()
        ]);
        $this->mailService->sendMail([
            'sender' => array('name' => 'ESGI', 'email' => 'ali.fatoori@gmail.com'),
            'htmlContent' => $emailTemplate,
            'to' => array(
                array('email' => $user->getEmail(), 'name' => "{$user->getFirstname()} {$user->getLastname()}")
            ),
            'subject' => 'Email confirmation'
        ]);
    }

    public function checkUserToken(string $token) {
        return $this->userRepository->findOneBy(['token' => $token]);
    }
}