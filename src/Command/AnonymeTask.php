<?php


namespace App\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;

class AnonymeTask extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'task-update-user-to-anonyme';

    protected function configure()
    {
        // ...
    }

    public function __construct(string $name = null,EntityManagerInterface $em,UserPasswordEncoderInterface $encoder,UserRepository $userRepository)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($this->userRepository->findOneByUsername('anonyme') === null && $this->userRepository->findOneByEmail('anonyme@anonyme.com') === null)
        {
            $user = new User();
            $user
                ->setUsername('anonyme')
                ->setPassword($this->encoder->encodePassword($user, 'anonyme'))
                ->setEmail('anonyme@anonyme.com')
                ->setRoles(['ROLE_USER'])
            ;

            $this->em->persist($user);

            $this->em->flush();
        }

        $RAW_QUERY = "UPDATE task SET task.user_id = (SELECT id FROM user WHERE username = 'anonyme' OR email = 'anonyme@anonyme.com') WHERE task.user_id IS NULL";

        $statement = $this->em->getConnection()->prepare($RAW_QUERY);

        if($statement->execute())
        {
            $section1 = $output->section();
            $section1->writeln("Its ok");
        }
    }
}