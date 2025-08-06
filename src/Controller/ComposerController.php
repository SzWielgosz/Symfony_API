<?php

namespace App\Controller;

use App\Entity\Composer;
use App\Repository\ComposerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class ComposerController extends AbstractController
{
    #[Route('/composer', name: 'app_composer', methods: ['GET'])]
    public function index(ComposerRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('/composer/{id}', name: 'app_composer_show', methods: ['GET'])]
    public function show(Composer $composer): JsonResponse
    {
        return $this->json($composer);
    }

    #[Route('/composer', name: 'app_composer_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        try {
            $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json');
        } catch (NotNormalizableValueException|\Exception $e) {
            return $this->json([
                'error' => 'Invalid input data: ' . $e->getMessage(),
            ], 422);
        }

        $errors = $validator->validate($composer);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 422);
        }

        $entityManager->persist($composer);
        $entityManager->flush();

        return $this->json($composer, 201);
    }

    #[Route('/composer/{id}', name: 'app_composer_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, Composer $composer, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $composer // It will not create a new composer but override existing one
        ]);
        $errors = $validator->validate($composer);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 422);
        }

        $entityManager->persist($composer);
        $entityManager->flush();
        return $this->json(data: $composer, status: 200);
    }

    #[Route('/composer/{id}', name: 'app_composer_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Composer $composer): JsonResponse
    {
        $entityManager->remove($composer);
        $entityManager->flush();
        return $this->json(data: '', status: 204);
    }
}
