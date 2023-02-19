<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Yaml\Yaml;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $userIndex = 0;
        $users = Yaml::parseFile(__DIR__ . '/users.yaml');
        foreach ($users as $userData) {
            $user = new User();
            $createdAt = new \DateTimeImmutable($userData['created_at']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setUsername($userData['username']);
            $user->setFirstname($userData['firstname']);
            $user->setLastname($userData['lastname']);
            $user->setEmail($userData['email']);
            $user->setPassword($hashedPassword);
            $user->setToken(Uuid::uuid4());
            $user->setRoles([...$user->getRoles(), 'IS_FULLY_AUTHENTICATED']);
            $user->setCreatedAt($createdAt);
            $manager->persist($user);
            $userIndex++;
            $this->addReference('user_' . $userIndex, $user);
        }

        $manager->flush();
    }
}

