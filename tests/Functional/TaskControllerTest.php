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

    public function testCreateTask()
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Nouvelle Task';
        $form['task[content]'] = 'Ajouter task';
        $this->client->submit($form);
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertCount(5,$this->entityManager->getRepository(Task::class)->findAll());
    }


    public function testUpdateTask()
    {
        $crawler = $this->client->request('GET', '/tasks/1/edit');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Corriger la tâche';
        $form['task[content]'] = 'Hello :)';
        $this->client->submit($form);

        static::assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }


    /**
     * @return void
     */
    public function testValidateTask(): void
    {
        // before
        static::assertCount(0,$this->entityManager->getRepository(Task::class)->findAllCheckValidate(1));
        static::assertCount(4,$this->entityManager->getRepository(Task::class)->findAllCheckValidate(0));

        $randomTask = $this->tasks[array_rand($this->tasks)];

        $this->client->request(
            'GET',
            '/tasks/'.$randomTask->getId().'/toggle'
        );
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);

        if(!$randomTask->getIsDone())
        {
            $this->assertEquals(
                'La tâche '.$randomTask->getTitle().' a bien été marquée comme faite.',
                current($flashes['success'])
            );
        }else {
            $this->assertEquals(
                'La tâche '.$randomTask->getTitle().' a bien été marquée comme non terminée..',
                current($flashes['success'])
            );
        }

        $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        // after
        static::assertCount(1,$this->entityManager->getRepository(Task::class)->findAllCheckValidate(1));
        static::assertCount(3,$this->entityManager->getRepository(Task::class)->findAllCheckValidate(0));
    }

    public function testGetTaskWhereItemDontExists()
    {
        $this->client->request('GET', '/tasks/555555555555');
        static::assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskActionAdminWhereIsNotAuthor()
    {
        static::assertCount(4,$this->entityManager->getRepository(Task::class)->findAll());

        $this->client->request('GET', '/tasks/4/delete');
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertCount(3,$this->entityManager->getRepository(Task::class)->findAll());
    }

    public function testDeleteTaskActionAdminWhereIsAuthor()
    {
        static::assertCount(4,$this->entityManager->getRepository(Task::class)->findAll());

        $this->client->request('GET', '/tasks/2/delete');
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertCount(3,$this->entityManager->getRepository(Task::class)->findAll());
    }

    public function testCannotDeleteTaskActionUserWhereIsNotAuthor()
    {
        $client = $this->loginUser($this->client,'user0');

        static::assertCount(4,$this->entityManager->getRepository(Task::class)->findAll());

        $client->request('GET', '/tasks/4/delete');
        static::assertSame(403, $this->client->getResponse()->getStatusCode());

        static::assertCount(4,$this->entityManager->getRepository(Task::class)->findAll());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}