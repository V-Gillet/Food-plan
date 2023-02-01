<?php

namespace App\Service;

use App\Entity\Meal;
use App\Service\NeedsCalculator;

class MealCalculator
{

    public function __construct(private NeedsCalculator $needsCalc)
    {
    }

    public function getMealCalories(Meal $meal): int
    {
        $proteinCalories = $meal->getProtein() * 4;
        $lipidCalories = $meal->getLipid() * 9;
        $carbCalories = $meal->getCarb() * 4;

        return $proteinCalories + $lipidCalories + $carbCalories;
    }
}
