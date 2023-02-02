<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\WeightHistoryRepository;
use DateTime;

class NeedsCalculator
{
    public const MASS_GAIN = 1.1;
    public const MASS_LOSS = 0.8;

    public function __construct(private WeightHistoryRepository $weightRepo)
    {
    }

    public function getMaintenanceCalories(User $user): int
    {
        $today = new DateTime('today');
        // Mifflin-St Jeor formula
        $weightHistory = $this->weightRepo->findOneBy(['user' => $user, 'date' => $today]);

        if ($user->getSexe() === 'male') {
            $maintenance = (10 * $weightHistory->getWeight() + 6.25 * $user->getHeight() - 5 * $user->getAge() + 5) * $user->getActivityRate();
        } else {
            $maintenance = (10 * $weightHistory->getWeight() + 6.25 * $user->getHeight() - 5 * $user->getAge() - 161) * $user->getActivityRate();
        }

        return $maintenance;
    }

    public function getGoalCalories(User $user): int
    {
        if ($user->getGoal() === 'gain') {
            $goalCalories = self::MASS_GAIN * $this->getMaintenanceCalories($user);
        } elseif ($user->getGoal() === 'lean') {
            $goalCalories = self::MASS_LOSS * $this->getMaintenanceCalories($user);
        } else {
            $goalCalories = $this->getMaintenanceCalories($user);
        }

        return $goalCalories;
    }

    public function getLipidRepartition(User $user): int
    {
        $today = new DateTime('today');
        $weightHistory = $this->weightRepo->findOneBy(['user' => $user, 'date' => $today]);

        return 0.8 * $weightHistory->getWeight();
    }

    public function getLipidCalories(User $user): int
    {
        return $this->getLipidRepartition($user) * 9;
    }

    public function getProteinRepartition(User $user): int
    {
        $today = new DateTime('today');
        $weightHistory = $this->weightRepo->findOneBy(['user' => $user, 'date' => $today]);
        return 1.5  * $weightHistory->getWeight();
    }

    public function getProteinCalories(User $user): int
    {
        return $this->getProteinRepartition($user) * 9;
    }

    public function getCarbsRepartition(User $user): int
    {
        return  $this->getGoalCalories($user) - ($this->getProteinCalories($user) + $this->getLipidCalories($user));
    }

    public function getCarbsCalories(User $user): int
    {
        return $this->getCarbsCalories($user) * 4;
    }
}
