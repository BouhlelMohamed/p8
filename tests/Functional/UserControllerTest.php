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
    public function testShowListUsers(): void
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


    /**
     * Test create User.
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        $crawler = $this->client->request(
            'GET',
            '/users/create'
        );

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'userTest';
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = 'userTest@test.com';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "L'utilisateur a bien été ajouté.",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }


    /**
     * @return void
     */
    public function testUpdateUser(): void
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user0']);
        $crawler = $this->client->request(
            'GET',
            '/users/'.$user->getId().'/edit'
        );

        $form = $crawler->selectButton('Modifier')->form();
        $form['edit_user[email]'] = 'user@user.com';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "L'utilisateur a bien été modifié",
            current($flashes['success'])
        );

        $this->client->followRedirect();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'user@user.com']);

        $this->assertCount(1, [$user]);
    }

    /**
     * @return void
     */
    public function testUpdatePassword(): void
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user0']);
        $crawler = $this->client->request(
            'GET',
            '/users/'.$user->getId().'/edit'
        );

        $form = $crawler->selectButton('Modifier le mot de passe')->form();
        $form['edit_password_user[password][first]'] = 'user@user.com';
        $form['edit_password_user[password][second]'] = 'user@user.com';
        $this->client->submit($form);

        $session = $this->client->getContainer()->get('session');
        $flashes = $session->getBag('flashes')->all();
        $this->assertArrayHasKey('success', $flashes);
        $this->assertCount(1, $flashes['success']);
        $this->assertEquals(
            "Le mot de passe a bien été modifié",
            current($flashes['success'])
        );

        $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}