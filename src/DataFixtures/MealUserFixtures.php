<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Meal;
use App\Service\MealCalculator;
use App\DataFixtures\MealFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\MealUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MealUserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private MealCalculator $mealCalculator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < MealFixtures::GENERIC_MEAL_LOOP; $i++) {
            $mealUser = new MealUser();
            $mealUser->setUser($this->getReference('user_0'));
            $mealUser->setMeal($this->getReference('meal_' . $faker->unique->numberBetween(0, MealFixtures::GENERIC_MEAL_LOOP)));

            $manager->persist($mealUser);
        }

        for ($j = 0; $j < MealFixtures::USER_MEAL_LOOP; $j++) {
            $mealUser = new MealUser();
            $mealUser->setUser($this->getReference('user_0'));
            $mealUser->setMeal($this->getReference('meal_user_0' . $j));

            $manager->persist($mealUser);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            MealFixtures::class
        ];
    }
}
