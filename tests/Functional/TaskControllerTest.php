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

class TaskControllerTest extends WebTestCase
{
    use FakeData,TruncateDB,Connection;
    /**
     * Helper to access test Client.
     *
     * @var KernelBrowser
     */
    private $client;

    /**
     * An EntityManager Instance.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Set up the client and the EntityManager.
     *
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
        $this->tasks = $this->entityManager->getRepository(Task::class)->findAll();
    }

    /**
     * @return void
     */
    public function testShowTasks(): void
    {
        $this->client->request(
            'GET',
            '/tasks'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * @return void
     */
    public function testShowTasksListValid(): void
    {
        $this->client->request(
            'GET',
            '/tasks/valid'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    /**
     * @return void
     */
    public function testShowTasksListNotValid(): void
    {
        $this->client->request(
            'GET',
            '/tasks/notvalid'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }


    /**
     * @return void
     */
    public function testValidateTask(): void
    {
        $randomTask = $this->tasks[array_rand($this->tasks)];
        $beforeChange = $randomTask->getIsDone();

        $this->client->request(
            'GET',
            '/tasks/'.$randomTask->getId().'/toggle'
        );
        dd($beforeChange,$randomTask->getIsDone());
        self::assertSame($randomTask->getId(),$beforeChange);

    }
}