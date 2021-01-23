<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomeControllerTest.
 */
class HomeControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function index(): void
    {
        $this->client->request('GET', '/');

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

    }
}