<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Meal;
use App\Service\MealCalculator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MealFixtures extends Fixture
{
    public function __construct(private MealCalculator $mealCalculator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $meal = new Meal();
            $meal->setName($faker->words(3, true));
            $meal->setDescription($faker->sentence($faker->randomNumber(2, true)));
            $meal->setOrigin($faker->country());
            $meal->setLipid($faker->numberBetween(2, 40));
            $meal->setProtein($faker->numberBetween(20, 60));
            $meal->setCarb($faker->numberBetween(20, 150));
            $meal->setCalories($this->mealCalculator->getMealCalories($meal));
            $meal->setType(Meal::MEAL_TYPE[$faker->numberBetween(0, 3)]);
            $meal->setIsRecipe(false);
            $meal->setDate($faker->dateTimeBetween('-1 week', '+1 week'));

            $manager->persist($meal);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [];
    }
}
