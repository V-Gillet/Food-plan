<?php

namespace App\Service;

use DateTime;
use App\Repository\MealRepository;
use App\Repository\MealUserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ComsumptionCalculator
{
    public function __construct(private MealRepository $mealRepository, private Security $security, private MealUserRepository $mealUserRepository)
    {
    }

    public function totalCaloryConsummed(): int
    {
        /** @var \App\Entity\User */
        $user = $this->security->getUser();
        dd($mealsUser = $this->mealUserRepository->findBy(['user' => $user]));

        $today = new DateTime('today');
        $meals = $this->mealRepository->findBy(['date' => $today]);
        $totalCalory = 0;
        foreach ($meals as $meal) {
            $totalCalory += $meal->getCalories();
        }

        return $totalCalory;
    }

    public function getCaloryLeft(): int
    {
        /** @var \App\Entity\User */
        $user = $this->security->getUser();
        if ($user->getGoal() === 'gain') {
            $caloryLeft = $user->getNeed()->getGainCalory() - $this->totalCaloryConsummed();
        } elseif ($user->getGoal() === 'lean') {
            $caloryLeft = $user->getNeed()->getLossCalory() - $this->totalCaloryConsummed();
        } else {
            $caloryLeft = $user->getNeed()->getMaintenanceCalory() - $this->totalCaloryConsummed();
        }
        return $caloryLeft;
    }
}
