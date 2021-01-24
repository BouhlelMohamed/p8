<?php


namespace App\Tests\Functional;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\FakeDataTrait\FakeData;
use App\Tests\FakeDataTrait\TruncateDB;
use App\Tests\FakeDataTrait\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FakeData,TruncateDB,Connection;

    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'test';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
    }


//    public function testCheckLogin()
//    {
//        $this->client->request('GET', '/login_check');
//        static::assertSame(200, $this->client->getResponse()->getStatusCode());
//    }

    public function testLoginWithNotValidData()
    {
        $crawler = $this->client->request('GET', '/login');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'TestNimporteQuoi';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        static::assertSame("Invalid credentials.", $crawler->filter('div.alert.alert-danger')->text());
    }


    /**
     * @return void
     */
    public function testLogout(): void
    {
        $crawler = $this->client->request('GET', '/');

        $link =  $crawler->filter('a.logout-button')
            ->link()
        ;

        $this->client->click($link);
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

    }

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
    }
}