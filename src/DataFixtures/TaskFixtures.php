<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

/**
 * @codeCoverageIgnore
 */
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 1; $i < 25; $i++) {
            $task = new Task();
            $task
                ->setTitle('task'.$i)
                ->setContent(
                    'Content' . $i . ' Lorem :).'
                )
                ->setIsDone(rand(0,1))
                ->setCreatedAt(new \DateTimeImmutable())
            ;
            $task->setUser($users[rand(0,3)]);
            if($i === 2)
            {
                $task->setUser(null);
            }
            $manager->persist($task);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array(UserFixtures::class); // fixture classes fixture is dependent on
    }

}