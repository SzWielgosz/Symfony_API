<?php

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\TestFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

abstract class AbstractApiTest extends WebTestCase //Abstract class to avoid repeating the same code in requests
{
    protected ?KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;
    protected static $testUserToken;

    protected function setUp(): void
    {
        static::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        if (static::$testUserToken) {
            $this->client->setServerParameter(
                'HTTP_Authorization',
                sprintf('Bearer %s', static::$testUserToken)
            );
        }
        parent::setUp();
    }

    public static function setUpBeforeClass(): void
    {
        static::ensureKernelShutdown();
        $client = static::createClient();
        $container = $client->getContainer();
        $entityManager = $container->get('doctrine')->getManager();

        $loader = new Loader();
        $loader->addFixture(new TestFixtures(
            $container->get('security.password_hasher')
        ));
        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'testuser',
                'password' => 'testpassword',
            ])
        );
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::$testUserToken = $data['token'];
    }

    public static function tearDownAfterClass(): void
    {
        self::ensureKernelShutdown();
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
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        return $this->client->getResponse();
    }

    protected function get(string $uri): Response
    {
        $this->client->request('GET', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        return $this->client->getResponse();
    }

    protected function put(string $uri, array $data): Response
    {
        $this->client->request('PUT', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        return $this->client->getResponse();
    }

    protected function delete(string $uri): Response
    {
        $this->client->request('DELETE', $uri, [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ]);

        return $this->client->getResponse();
    }
}
