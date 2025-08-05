<?php

namespace App\Controller;

use App\Dto\UserReadDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users_show', methods: ['GET'])]
    public function getUsers(EntityManagerInterface $entityManager): JsonResponse
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $usersReadDto = [];
        foreach ($users as $user) {
            $userReadDto = new UserReadDto($user->getId(), $user->getUsername(), $user->getRoles());
            $usersReadDto[] = $userReadDto;
        }
        return $this->json($usersReadDto, 200, []);
    }
}
