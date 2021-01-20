<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

class TaskTest extends TestCase
{
    public function setUp(): void
    {
        $this->task = new Task();
    }

    /**
     * @test
     */
    public function getId()
    {
        $this->assertEquals(null, $this->task->getId());
    }

    /**
     * @test
     */
    public function entityTask()
    {
        $this->assertInstanceOf(Task::class, $this->task);
    }

    /**
     * @test
     */
    public function getAndSetTitle()
    {
        $this->assertEquals(null, $this->task->getTitle());
        $this->task->setTitle('TEST');
        $this->assertEquals('TEST', $this->task->getTitle());
    }

    /**
     * @test
     */
    public function getAndSetContent()
    {
        $this->assertEquals(null, $this->task->getContent());
        $this->task->setContent('Content');
        $this->assertEquals('Content', $this->task->getContent());
    }

    /**
     * @test
     */
    public function getAndSetIsDone()
    {
        $this->assertEquals(null, $this->task->getIsDone());
        $this->task->setIsDone(1);
        $this->assertEquals(1, $this->task->getIsDone());
    }

    /**
     * @test
     */
    public function getAndSetCreatedAt()
    {
        $datetime = new DateTime('2020-10-10 00:00:00');
        $this->assertEquals(null, $this->task->getCreatedAt());
        $this->task->setCreatedAt($datetime);
        $this->assertEquals($datetime, $this->task->getCreatedAt());
    }

    /**
     * @test
     */
    public function getAndSetUser()
    {
        $this->assertEquals(null, $this->task->getUser());
        $user = new User();
        $this->task->setUser($user);
        $this->assertEquals($user, $this->task->getUser());
    }
}