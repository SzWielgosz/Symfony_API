<?php

namespace App\Tests;

use AbstractApiTest;
use App\Entity\Symphony;

class SymphonyTest extends AbstractApiTest
{

    private static $testSymphony1 = [
        'name' => 'First Symphony',
        'description' => 'First Symphony description',
    ];

    private static $testSymphony2 = [
        'name' => 'Second Symphony',
        'description' => 'Second Symphony description',
    ];


    public function testCreate(): void
    {

        $testComposer = [
            'firstName' => 'Ludwig',
            'lastName' => 'Beethoven',
            'dateOfBirth' => '1770-12-17',
            'countryCode' => 'DE'
        ];
        $composerResponse = $this->post('/composer', $testComposer);
        $composer = json_decode($composerResponse->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();

        static::$testSymphony1['composerId'] = $composer['id'];
        static::$testSymphony2['composerId'] = $composer['id'];


        $response1 = $this->post('/symphony', static::$testSymphony1);
        $data1 = json_decode($response1->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();

        static::$testSymphony1['id'] = $data1['id'] ?? null;

        $response2 = $this->post('/symphony', static::$testSymphony2);

        $data2 = json_decode($response2->getContent(), true);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();

        static::$testSymphony2['id'] = $data2['id'] ?? null;
    }

    /**
     * @depends testCreate
     */
    public function testRead(): void
    {
        $response = $this->get('/symphony');
        $content = $response->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        $data = json_decode($content, true);

        $this->assertEquals(static::$testSymphony1['id'], $data[0]['id']);
        $this->assertEquals(static::$testSymphony1['name'], $data[0]['name']);
        $this->assertEquals(static::$testSymphony1['description'], $data[0]['description']);

        $this->assertEquals(static::$testSymphony2['id'], $data[1]['id']);
        $this->assertEquals(static::$testSymphony2['name'], $data[1]['name']);
        $this->assertEquals(static::$testSymphony2['description'], $data[1]['description']);
    }

    /**
     * @depends testCreate
     */
    public function testReadSpecific(): void
    {
        $response = $this->get('/symphony/' . static::$testSymphony1['id']);
        $content = $response->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        $data = json_decode($content, true);

        $this->assertEquals(static::$testSymphony1['id'], $data['id']);
        $this->assertEquals(static::$testSymphony1['name'], $data['name']);
        $this->assertEquals(static::$testSymphony1['description'], $data['description']);
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(): void
    {
        $newData = [
            'name' => 'Updated Symphony',
            'description' => 'Updated Symphony description',
            'created_at' => '1810-01-01',
        ];

        $findSymphony = $this->entityManager->getRepository(Symphony::class)->find(static::$testSymphony1['id']);

        $data = $this->put('/symphony/' . $findSymphony->getId(), $newData);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        $updatedSymphony = json_decode($data->getContent(), true);

        $this->assertEquals($newData['name'], $updatedSymphony['name']);
        $this->assertEquals($newData['description'], $updatedSymphony['description']);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(): void
    {
        $this->delete('/symphony/' . static::$testSymphony1['id']);

        $this->assertResponseStatusCodeSame(204);
        $this->assertResponseIsSuccessful();
    }
}
