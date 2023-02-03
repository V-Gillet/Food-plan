<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Meal;
use App\Entity\MealUser;
use App\Service\MealCalculator;
use App\DataFixtures\MealFixtures;
use App\DataFixtures\UserFixtures;
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
            $mealUser->setDate($faker->dateTimeBetween('-4 week', '-1 day'));
            $manager->persist($mealUser);
        }

        for ($j = MealFixtures::GENERIC_MEAL_LOOP; $j < MealFixtures::GENERIC_RECIPE_LOOP; $j++) {
            $mealUser = new MealUser();
            $mealUser->setUser($this->getReference('user_1'));
            $mealUser->setMeal($this->getReference('meal_' . $j));
            $mealUser->setDate($faker->dateTimeBetween('-4 week', '-1 day'));
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
