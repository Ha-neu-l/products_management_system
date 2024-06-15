<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
         $user = new User();
         $Apassword="admin2022";
         $hashedPassword= $this->passwordHasher
              ->hashPassword($user,$Apassword);
         $user->setUsername('admin');
         $user->setPassword($hashedPassword);
         $user->setRoles(['ROLE_ADMIN']);
         $manager->persist($user);

        $manager->flush();
    }
}
