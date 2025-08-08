<?php

namespace App\Controller;

use App\Dto\TagReadDto;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TagController extends AbstractController
{
    #[Route('/tag', name: 'app_tag', methods: ['GET'])]
    public function index(TagRepository $repo): JsonResponse
    {
        $data = $repo->findAll();
        $tagsReadDto = [];
        foreach ($data as $tag) {
            $tagRead = new TagReadDto($tag->getId(), $tag->getName());
            $tagsReadDto[] = $tagRead;
        }
        return $this->json(data: $tagsReadDto, status: 200);
    }
}
