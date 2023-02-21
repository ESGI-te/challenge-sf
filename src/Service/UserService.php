<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utilities\Constants;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{

    private string $imageDirectory;
    private FileService $fs;
    private UserRepository $userRepository;

    public function __construct(
        string $imageDirectory,
        UserRepository $userRepository,
        FileService $fs
    ) {
        $this->userRepository = $userRepository;
        $this->fs = $fs;
        $this->imageDirectory = $imageDirectory;
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
}