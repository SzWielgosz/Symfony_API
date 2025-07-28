<?php

namespace App\Controller;

use App\Entity\Composer;
use App\Repository\ComposerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function create(EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json');
        $entityManager->persist($composer);
        $entityManager->flush();

        return $this->json(data: $composer, status: 201);
    }

    #[Route('/composer/{id}', name: 'app_composer_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, SerializerInterface $serializer, Composer $composer, Request $request): JsonResponse
    {
        $composer = $serializer->deserialize($request->getContent(), Composer::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $composer // It will not create a new composer but override existing one
        ]);
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
