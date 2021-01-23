<?php
namespace App\Tests\FakeDataTrait;

use App\Entity\User;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

trait FakeData{

    public function addFakeDataUser(EntityManagerInterface $entityManager)
    {
        for($i = 0; $i <= 3; $i++)
        {
            $user = new User();
            $user->setUsername('user' . $i)
            ->setEmail('user' . $i . '@user.com')
            ->setPassword(
                '$2y$13$Q550MpE6pPh10MAfHD.16ugtsEq.3i4aMaMCtI2fUmpWItWV.7phS'
            )
            ->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
        }
       $entityManager->flush();

    }

    public function addFakeDataTask()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $users = $entityManager
            ->getRepository(User::class)->findAll();

        for($i = 0; $i <= 3; $i++)
        {
            $task = new Task();
            $task->setTitle('task')
                ->setContent('task2')
                ->setIsDone(0)
            ->setUser($users[$i]);
            $entityManager->persist($task);
        }
        $entityManager->flush();
    }
}