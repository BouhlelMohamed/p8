<?php

namespace App\Tests\Unit;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    private $user;

    public function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * @test
     */
    public function getId()
    {
        $this->assertEquals(null, $this->user->getId());
    }

    /**
     * @test
     */
    public function entityUser()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    /**
     * @test
     */
    public function getAndSetUsername()
    {
        $this->assertEquals(null, $this->user->getUsername());
        $this->user->setUsername('TEST');
        $this->assertEquals('TEST', $this->user->getUsername());
    }

    /**
     * @test
     */
    public function getAndSetPassword()
    {
        $this->assertEquals(null, $this->user->getPassword());
        $this->user->setPassword('PASSWORD');
        $this->assertEquals('PASSWORD', $this->user->getPassword());
    }

    /**
     * @test
     */
    public function getAndSetEmail()
    {
        $this->assertEquals(null, $this->user->getEmail());
        $this->user->setEmail('email@gmail.com');
        $this->assertEquals('email@gmail.com', $this->user->getEmail());
    }

    /**
     * @test
     */
    public function getAndAddAndRemoveTasks()
    {
        $this->assertInstanceOf(Collection::class, $this->user->getTasks());

        $task = new Task;
        $task->setTitle('Task');
        $task->setContent('TaskContent');

        $this->user->addTask($task);
        $this->assertCount(1,$this->user->getTasks());

        $this->user->removeTask($task);
        $this->assertCount(0,$this->user->getTasks());
    }

    /**
     * @test
     */
    public function getAndSetRoles()
    {
        $this->assertSame(["ROLE_USER"], $this->user->getRoles());

        $this->user->setRoles(["ROLE_TEST1"]);
        $this->user->setRoles(["ROLE_TEST2"]);

        $this->assertCount(2,$this->user->getRoles());
    }
}