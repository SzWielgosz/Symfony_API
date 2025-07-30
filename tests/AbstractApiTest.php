<?php

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiTest extends WebTestCase //Abstract class to avoid repeating the same code in requests
{
    protected ?KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public static function tearDownAfterClass(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $purger = new ORMPurger($entityManager);
        $purger->purge();

        $entityManager->close();
        parent::tearDownAfterClass();
    }


    protected function post(string $uri, array $data): Response
    {
        $this->client->request('POST', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        return $this->client->getResponse();
    }

    protected function get(string $uri): Response
    {
        $this->client->request('GET', $uri);
        return $this->client->getResponse();
    }

    protected function put(string $uri, array $data): Response
    {
        $this->client->request('PUT', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));

        return $this->client->getResponse();
    }

    protected function delete(string $uri): Response
    {
        $this->client->request('DELETE', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ]);

        return $this->client->getResponse();
    }
}
