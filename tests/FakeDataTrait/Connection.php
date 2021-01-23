<?php
namespace App\Tests\FakeDataTrait;

use App\Entity\User;
use App\Entity\Task;

trait Connection
{
    public function loginUser($client,$username='user1',$password='password')
    {
        $testIndex = $client->request(
            'GET',
            '/login'
        );

        $form = $testIndex->selectButton('Se connecter')->form();
        $form['_username'] = $username;
        $form['_password'] = $password;
        $client->submit($form);
        return $client;
    }
}
