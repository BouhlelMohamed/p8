<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\FakeDataTrait\FakeData;
use App\Tests\FakeDataTrait\TruncateDB;
use App\Tests\FakeDataTrait\Connection
    ;
use App\Entity\User;
use App\Entity\Task;
use App\Repository\UserRepository;


/**
 * Class HomeControllerTest.
 */
class HomeControllerTest extends WebTestCase
{
    use FakeData,TruncateDB,Connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createClient(
            ['environment' => 'test']
        );

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->truncateEntities([
            Task::class,
            User::class,
        ]);

        $this->addFakeDataUser($this->entityManager);
        $this->addFakeDataTask($this->entityManager);
    }

    /**
     * @test
     */
    public function index(): void
    {
        $this->client->request('GET', '/');

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginUser($this->client);

        $this->client->request('GET', '/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}