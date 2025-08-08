<?php

namespace App\Tests;

use AbstractApiTest;
use App\Entity\Tag;

class TagTest extends AbstractApiTest
{
    public function testRead(): void
    {
        $tag = new Tag();
        $tag->setName('Classical');
        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $seponse = $this->get('/api/tag');
        $data = json_decode($seponse->getContent(), true);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $data);
        $this->assertEquals('Classical', $data[0]['name']);
    }
}
