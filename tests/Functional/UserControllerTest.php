<?php


namespace App\Tests\Functional;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\FakeDataTrait\FakeData;
use App\Tests\FakeDataTrait\TruncateDB;
use App\Tests\FakeDataTrait\Connection;

class UserControllerTest extends WebTestCase
{
    use FakeData,TruncateDB,Connection;
    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createClient(['environment' => 'test']);
        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->client = $this->loginUser($this->client);

        $this->truncateEntities([
            Task::class,
            User::class,
        ]);

        $this->addFakeDataUser($this->entityManager);
        $this->addFakeDataTask($this->entityManager);
        $this->users = $this->entityManager->getRepository(User::class)->findAll();
    }

    /**
     * @return void
     */
    public function testShowUsers(): void
    {
        $this->client->request(
            'GET',
            '/users'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }
}