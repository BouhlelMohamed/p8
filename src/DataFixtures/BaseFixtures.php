<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class BaseFixtures extends Fixture
{

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setUsername('anonyme')
            ->setPassword($this->encoder->encodePassword($user, 'anonyme'))
            ->setEmail('anonyme@anonyme.com')
            ->setRoles(['ROLE_USER'])
        ;

        $manager->persist($user);

        $admin = new User();
        $admin
            ->setUsername("admin")
            ->setPassword($this->encoder->encodePassword($admin, 'admin'))
            ->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);

        $manager->flush();
    }
}