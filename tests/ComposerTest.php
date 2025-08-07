<?php

namespace App\Tests;

use AbstractApiTest;
use App\Entity\Composer;

class ComposerTest extends AbstractApiTest
{

    private static $testComposer1 = [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'dateOfBirth' => '1810-10-17',
        'countryCode' => 'US'
    ];

    private static $testComposer2 = [
        'firstName' => 'Jane',
        'lastName' => 'Smith',
        'dateOfBirth' => '1820-05-20',
        'countryCode' => 'US'
    ];


    public function testCreate(): void
    {
        $response1 = $this->post('/api/composer', static::$testComposer1);

        $data1 = json_decode($response1->getContent(), true);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();

        static::$testComposer1['id'] = $data1['id'] ?? null;


        $response2 = $this->post('/api/composer', static::$testComposer2);
        $data2 = json_decode($response2->getContent(), true);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseIsSuccessful();

        static::$testComposer2['id'] = $data2['id'] ?? null;
    }

    public function testCreateFailed(): void
    {
        $invalidComposer = [
            'firstName' => 'Invalid',
            'lastName' => 'Composer',
            'dateOfBirth' => 'invalid-date',
            'countryCode' => 'XX'
        ];
        $this->post('/api/composer', $invalidComposer);
        $this->assertResponseStatusCodeSame(422);
    }

    /**
     * @depends testCreate
     */
    public function testRead(): void
    {
        $response = $this->get('/api/composer');
        $content = $response->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        $data = json_decode($content, true);

        $this->assertEquals(static::$testComposer1['id'], $data[0]['id']);
        $this->assertEquals(static::$testComposer1['firstName'], $data[0]['firstName']);
        $this->assertEquals(static::$testComposer1['lastName'], $data[0]['lastName']);
        $this->assertEquals(static::$testComposer1['dateOfBirth'], $data[0]['dateOfBirth']);
        $this->assertEquals(static::$testComposer1['countryCode'], $data[0]['countryCode']);

        $this->assertEquals(static::$testComposer2['id'], $data[1]['id']);
        $this->assertEquals(static::$testComposer2['firstName'], $data[1]['firstName']);
        $this->assertEquals(static::$testComposer2['lastName'], $data[1]['lastName']);
        $this->assertEquals(static::$testComposer2['dateOfBirth'], $data[1]['dateOfBirth']);
        $this->assertEquals(static::$testComposer2['countryCode'], $data[1]['countryCode']);
    }

    /**
     * @depends testCreate
     */
    public function testReadSpecific(): void
    {
        $response = $this->get('/api/composer/' . static::$testComposer1['id']);
        $content = $response->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        $data = json_decode($content, true);

        $this->assertEquals(static::$testComposer1['id'], $data['id']);
        $this->assertEquals(static::$testComposer1['firstName'], $data['firstName']);
        $this->assertEquals(static::$testComposer1['lastName'], $data['lastName']);
        $this->assertEquals(static::$testComposer1['dateOfBirth'], $data['dateOfBirth']);
        $this->assertEquals(static::$testComposer1['countryCode'], $data['countryCode']);
    }

    /**
     * @depends testCreate
     */
    public function testUpdate(): void
    {
        $newData = [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'countryCode' => 'US'
        ];

        $findComposer = $this->entityManager->getRepository(Composer::class)->find(static::$testComposer1['id']);

        $data = $this->put('/api/composer/' . $findComposer->getId(), $newData);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();

        $updatedComposer = json_decode($data->getContent(), true);

        $this->assertEquals($newData['firstName'], $updatedComposer['firstName']);
        $this->assertEquals($newData['lastName'], $updatedComposer['lastName']);
        $this->assertEquals($newData['dateOfBirth'], $updatedComposer['dateOfBirth']);
        $this->assertEquals($newData['countryCode'], $updatedComposer['countryCode']);
    }

    /**
     * @depends testCreate
     */
    public function testDelete(): void
    {
        $this->delete('/api/composer/' . static::$testComposer1['id']);

        $this->assertResponseStatusCodeSame(204);
        $this->assertResponseIsSuccessful();
    }
}
