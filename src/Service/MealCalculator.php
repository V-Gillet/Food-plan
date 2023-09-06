<?php

namespace App\Service;

use App\Entity\Meal;
use App\Service\NeedsCalculator;

class MealCalculator
{
    final public const PROTEIN_CALORIC_VALUE = 4;
    final public const CARB_CALORIC_VALUE = 4;
    final public const LIPID_CALORIC_VALUE = 9;

    public function getMealCalories(Meal $meal): int
    {
        $proteinCalories = $meal->getProtein() * self::PROTEIN_CALORIC_VALUE;
        $lipidCalories = $meal->getLipid() * self::LIPID_CALORIC_VALUE;
        $carbCalories = $meal->getCarb() * self::CARB_CALORIC_VALUE;

        return $proteinCalories + $lipidCalories + $carbCalories;
    }
}
