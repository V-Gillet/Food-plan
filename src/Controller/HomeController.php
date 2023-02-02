<?php

namespace App\Controller;

use DateTime;
use App\Entity\Need;
use App\Service\ChartJS;
use App\Entity\WeightHistory;
use App\Service\NeedsCalculator;
use App\Form\UserInformationsType;
use App\Repository\NeedRepository;
use App\Repository\UserRepository;
use App\Service\ComsumptionCalculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        UserRepository $userRepository,
        NeedsCalculator $needsCalculator,
        NeedRepository $needRepository,
        ChartJS $chartJS,
        ComsumptionCalculator $consumptionCalc
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        $form = $this->createForm(UserInformationsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weight = new WeightHistory;
            $today = new DateTime('today');

            /** @var float */
            $tempWeight = $form->getData()->getTempWeight();

            $weight->setUser($user);
            $weight->setWeight($tempWeight);
            $weight->setDate($today);

            $user->addWeight($weight);
            $userRepository->save($user, true);

            $need = new Need;
            $need->setUser($user);
            $need->setMaintenanceCalory($needsCalculator->getMaintenanceCalories($user));
            if ($form->getData()->getGoal() === 'gain') {
                $need->setGainCalory($needsCalculator->getGoalCalories($user));
            } elseif ($form->getData()->getGoal() === 'lean') {
                $need->setLossCalory($needsCalculator->getGoalCalories($user));
            }
            $need->setLipid($needsCalculator->getLipidRepartition($user));
            $need->setProtein($needsCalculator->getProteinRepartition($user));
            $need->setCarb($needsCalculator->getCarbsRepartition($user));
            $needRepository->save($need, true);

            return $this->redirectToRoute('app_home');
        }
        if ($user->getNeed() !== null) {
            if ($user->getGoal() === 'gain') {
                $caloriesChart = $chartJS->gainCaloriesUserChart($user);
            } elseif ($user->getGoal() === 'lean') {
                $caloriesChart = $chartJS->leanCaloriesUserChart($user);
            } else {
                $caloriesChart = $chartJS->maintenanceCaloriesUserChart($user);
            }
            $proteinChart = $chartJS->proteinUserChart($user);
            $lipidChart = $chartJS->lipidUserChart($user);
            $carbChart = $chartJS->carbUserChart($user);
        } else {
            $proteinChart = '';
            $lipidChart = '';
            $carbChart = '';
            $caloriesChart = '';
        }




        return $this->render('home/index.html.twig', [
            'form' => $form,
            'proteinChart' => $proteinChart,
            'lipidChart' => $lipidChart,
            'carbChart' => $carbChart,
            'caloriesChart' => $caloriesChart,
            'caloryLeft' => $consumptionCalc->getCaloryLeft()
        ]);
    }
}
