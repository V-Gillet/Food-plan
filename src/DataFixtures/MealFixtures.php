<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Meal;
use App\Service\MealCalculator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class MealFixtures extends Fixture
{
    public const GENERIC_MEAL_LOOP = 50;
    public const GENERIC_RECIPE_LOOP = 100;
    public const USER_MEAL_LOOP = 3;

    public function __construct(private MealCalculator $mealCalculator)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::GENERIC_MEAL_LOOP; $i++) {
            $meal = new Meal();
            $meal->setName($faker->words(3, true));
            $meal->setDescription($faker->sentence($faker->randomNumber(3, true)));
            $meal->setOrigin($faker->countryCode());
            $meal->setLipid($faker->numberBetween(2, 40));
            $meal->setProtein($faker->numberBetween(20, 60));
            $meal->setCarb($faker->numberBetween(20, 150));
            $meal->setCalories($this->mealCalculator->getMealCalories($meal));
            $meal->setType(Meal::MEAL_TYPE[$faker->numberBetween(0, 3)]);
            $meal->setIsRecipe(false);
            $this->addReference('meal_' . $i, $meal);

            $manager->persist($meal);
        }

        // for User demo
        for ($j = 0; $j < self::USER_MEAL_LOOP; $j++) {
            $meal = new Meal();
            $meal->setName($faker->words(3, true));
            $meal->setDescription($faker->sentence($faker->randomNumber(3, true)));
            $meal->setOrigin($faker->countryCode());
            $meal->setLipid($faker->numberBetween(2, 40));
            $meal->setProtein($faker->numberBetween(20, 60));
            $meal->setCarb($faker->numberBetween(20, 150));
            $meal->setCalories($this->mealCalculator->getMealCalories($meal));
            $meal->setType(Meal::MEAL_TYPE[$j]);
            $meal->setIsRecipe(false);
            $this->addReference('meal_user_0' . $j, $meal);

            $manager->persist($meal);
        }

        // Is recipe true
        for ($k = self::GENERIC_MEAL_LOOP; $k <= self::GENERIC_RECIPE_LOOP; $k++) {
            $meal = new Meal();
            $meal->setName($faker->words(3, true));
            $meal->setDescription($faker->sentence($faker->randomNumber(3, true)));
            $meal->setOrigin($faker->countryCode());
            $meal->setLipid($faker->numberBetween(2, 40));
            $meal->setProtein($faker->numberBetween(20, 60));
            $meal->setCarb($faker->numberBetween(20, 150));
            $meal->setCalories($this->mealCalculator->getMealCalories($meal));
            $meal->setType(Meal::MEAL_TYPE[$j]);
            $meal->setIsRecipe(true);
            $this->addReference('meal_' . $k, $meal);

            $manager->persist($meal);
        }

        $manager->flush();
    }
}
