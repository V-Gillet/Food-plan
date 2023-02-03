<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\WeightHistoryRepository;
use DateTime;

class NeedsCalculator
{
    public const MASS_GAIN = 1.1;
    public const MASS_LOSS = 0.8;
    public const PROTEIN_RATE = 0.55;
    public const LIPID_RATE = 0.25;
    public const CARB_RATE = 0.2;

    public function __construct(private WeightHistoryRepository $weightRepo)
    {
    }

    public function getMaintenanceCalories(User $user): int
    {
        $today = new DateTime('today');
        // Mifflin-St Jeor formula
        $weightHistory = $this->weightRepo->findOneBy(['user' => $user, 'date' => $today]);

        if ($user->getCharacteristics()->getSexe() === 'male') {
            $maintenance = (10 * $weightHistory->getWeight() + 6.25 * $user->getCharacteristics()->getHeight() - 5 * $user->getCharacteristics()->getAge() + 5) * $user->getCharacteristics()->getActivityRate();
        } else {
            $maintenance = (10 * $weightHistory->getWeight() + 6.25 * $user->getCharacteristics()->getHeight() - 5 * $user->getCharacteristics()->getAge() - 161) * $user->getCharacteristics()->getActivityRate();
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
        return self::LIPID_RATE * $this->getGoalCalories($user) / 9;
    }

    public function getLipidCalories(User $user): int
    {
        return $this->getLipidRepartition($user) * 9;
    }

    public function getProteinRepartition(User $user): int
    {
        return self::PROTEIN_RATE * $this->getGoalCalories($user) / 4;
    }

    public function getProteinCalories(User $user): int
    {
        return $this->getProteinRepartition($user) * 4;
    }

    public function getCarbsRepartition(User $user): int
    {
        return self::CARB_RATE * $this->getGoalCalories($user) / 4;
    }

    public function getCarbsCalories(User $user): int
    {
        return $this->getCarbsRepartition($user) * 4;
    }
}
