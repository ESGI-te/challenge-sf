<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utilities\Constants;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{

    private string $imageDirectory;
    private FileService $fs;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(
        string $imageDirectory,
        UserRepository $userRepository,
        FileService $fs,
        EntityManagerInterface $em

    ) {
        $this->userRepository = $userRepository;
        $this->fs = $fs;
        $this->imageDirectory = $imageDirectory;
        $this->em = $em;
    }

    /**
     * @throws \Exception
     */
    public function update(String $email, UploadedFile $avatar) : void
    {
        $avatarPath = $this->fs->upload($avatar, $this->imageDirectory, Constants::IMG_EXTENSIONS);
        $UserObject = $this->userRepository->findOneBy(['email' => $email]);
        $this->deleteAvatarFile($UserObject);
        $UserObject->setAvatar($avatarPath);
        $this->userRepository->save($UserObject,true);
    }


    public function getFile(User $user)
    {
        $avatar = $user->getAvatar();
        return $this->fs->get($avatar,$this->imageDirectory);
    }

    public function deleteAvatarFile(User $user)
    {
        $this->fs->delete($this->getFile($user), directory: $this->imageDirectory);
    }

    public function getUsersWithDetails(): array
    {
        $query = $this->em->createQuery('
        SELECT u.id, u.firstname, u.lastname, u.email, u.roles, u.username, p.name AS plan
        FROM App\Entity\User u
        JOIN u.plan p
        ORDER BY u.email
        ');
        return $query->getResult();
    }
}