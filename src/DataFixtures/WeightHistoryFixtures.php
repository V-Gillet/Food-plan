<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use Faker\Factory;
use App\Entity\Meal;
use App\Entity\WeightHistory;
use App\Service\MealCalculator;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WeightHistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const WEIGHT_LOOP = 30;
    public const DARTH_VADOR_STARTING_WEIGHT = 89;

    public function __construct(private MealCalculator $mealCalculator)
    {
    }

    public function load(ObjectManager $manager): void
    {
//        $faker = Factory::create();
//
//        for ($i = self::WEIGHT_LOOP; $i > 0; $i--) {
//            $dateinterval = new DateInterval('P' . $i . 'D');
//            $today = new DateTime('today');
//
//            /** @var DateTime */
//            $weightDate = $today->sub($dateinterval);
//
//            $weightHistory = new WeightHistory();
//            $weightHistory->setWeight(SELF::DARTH_VADOR_STARTING_WEIGHT + $faker->randomFloat(2, 0, 1));
//            $weightHistory->setUser($this->getReference('user_0'));
//            $weightHistory->setDate($weightDate);
//
//            $manager->persist($weightHistory);
//        }
//
//        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class,];
    }
}
