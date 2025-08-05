<?php

namespace App\Controller;

use App\Entity\User;
use App\Dto\RegisterUserDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function index(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        try {
            $dto = $serializer->deserialize($request->getContent(), RegisterUserDto::class, 'json');
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid JSON or data format: ' . $e->getMessage()], 400);
        }

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 422);
        }

        $user = new User();
        $user->setUsername($dto->username);
        $user->setPassword($userPasswordHasher->hashPassword($user, $dto->password));
        $user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ]
        ], 201);
    }
}
