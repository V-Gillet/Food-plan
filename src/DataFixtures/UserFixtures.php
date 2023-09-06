<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        //User1, il a déjà un compte. Il a 3 repas ajd PENSER A REMPLIR SES BESOINS MANUELLEMENT
        $user = new User();
        $user->setEmail('pepsi-man@mail.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );
        $user->setPassword($hashedPassword);
        $user->setFirstname('Darth');
        $user->setLastname('Vador');
        $this->addReference('user_0', $user);
        $manager->persist($user);

        //User2, il a déjà un compte et mais ne s'est pas encore connecté
        $user = new User();
        $user->setEmail('luke@mail.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'totototo'
        );
        $user->setPassword($hashedPassword);
        $user->setFirstname('Luke');
        $user->setLastname('Skywalker');
        $this->addReference('user_1', $user);

        $manager->persist($user);

        $manager->flush();
    }
}
