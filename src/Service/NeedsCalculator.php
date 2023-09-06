<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\WeightHistoryRepository;
use DateTime;

class NeedsCalculator
{
    final public const MASS_GAIN = 1.1;
    final public const MASS_LOSS = 0.8;
    final public const PROTEIN_RATE = 0.55;
    final public const LIPID_RATE = 0.25;
    final public const CARB_RATE = 0.2;

    public function __construct(private WeightHistoryRepository $weightRepo)
    {
    }

    public function getMaintenanceCalories(User $user): int
    {
        $today = new DateTime('today');
        // Mifflin-St Jeor formula
        $weightHistory = $this->weightRepo->findOneBy(['user' => $user, 'date' => $today]);

        if ($user->getCharacteristics()->getSexe() === 'male') {
            $maintenance =
                (10 * $weightHistory->getWeight() + 6.25 * $user->getCharacteristics()->getHeight() - 5 * $user->getCharacteristics()->getAge() + 5)
                * $user->getCharacteristics()->getActivityRate();
        } else {
            $maintenance = (10 * $weightHistory->getWeight() + 6.25 * $user->getCharacteristics()->getHeight() - 5 * $user->getCharacteristics()->getAge() - 161)
                * $user->getCharacteristics()->getActivityRate();
        }

        return $maintenance;
    }

    public function getGoalCalories(User $user): int
    {
        if ($user->getCharacteristics()->getGoal() === 'gain') {
            $goalCalories = self::MASS_GAIN * $this->getMaintenanceCalories($user);
        } elseif ($user->getCharacteristics()->getGoal() === 'lean') {
            $goalCalories = self::MASS_LOSS * $this->getMaintenanceCalories($user);
        } else {
            $goalCalories = $this->getMaintenanceCalories($user);
        }

        return $goalCalories;
    }

    public function getLipidRepartition(User $user): int
    {
        return self::LIPID_RATE * $this->getGoalCalories($user) / MealCalculator::LIPID_CALORIC_VALUE;
    }

    public function getLipidCalories(User $user): int
    {
        return $this->getLipidRepartition($user) * MealCalculator::LIPID_CALORIC_VALUE;
    }

    public function getProteinRepartition(User $user): int
    {
        return self::PROTEIN_RATE * $this->getGoalCalories($user) / MealCalculator::PROTEIN_CALORIC_VALUE;
    }

    public function getProteinCalories(User $user): int
    {
        return $this->getProteinRepartition($user) * MealCalculator::PROTEIN_CALORIC_VALUE;
    }

    public function getCarbsRepartition(User $user): int
    {
        return self::CARB_RATE * $this->getGoalCalories($user) / MealCalculator::CARB_CALORIC_VALUE;
    }

    public function getCarbsCalories(User $user): int
    {
        return $this->getCarbsRepartition($user) * MealCalculator::CARB_CALORIC_VALUE;
    }
}
