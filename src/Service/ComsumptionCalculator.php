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
        $today = new DateTime('today');

        $mealsUsers = $this->mealUserRepository->findBy(['user' => $user, 'date' => $today]);
        $totalCalory = 0;
        foreach ($mealsUsers as $mealUser) {
            $totalCalory += $mealUser->getMeal()->getCalories();
        }

        return $totalCalory;
    }

    public function getCaloryLeft(): int
    {
        /** @var \App\Entity\User */
        $user = $this->security->getUser();
        if ($user->getCharacteristics()->getGoal() === 'gain') {
            $caloryLeft = $user->getNeed()->getGainCalory() - $this->totalCaloryConsummed();
        } elseif ($user->getCharacteristics()->getGoal() === 'lean') {
            $caloryLeft = $user->getNeed()->getLossCalory() - $this->totalCaloryConsummed();
        } else {
            $caloryLeft = $user->getNeed()->getMaintenanceCalory() - $this->totalCaloryConsummed();
        }
        return $caloryLeft;
    }
}
