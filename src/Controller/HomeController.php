<?php

namespace App\Controller;

use App\Entity\Characteristic;
use App\Entity\Need;
use App\Entity\User;
use App\Entity\WeightHistory;
use App\Form\CharacteristicType;
use App\Form\WeightHistoryType;
use App\Repository\CharacteristicRepository;
use App\Repository\NeedRepository;
use App\Repository\UserRepository;
use App\Repository\WeightHistoryRepository;
use App\Service\ChartJS;
use App\Service\ComsumptionCalculator;
use App\Service\NeedsCalculator;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request                  $request,
        UserRepository           $userRepository,
        NeedsCalculator          $needsCalculator,
        NeedRepository           $needRepository,
        ChartJS                  $chartJS,
        ComsumptionCalculator    $consumptionCalc,
        WeightHistoryRepository  $weightHistoRepo,
        CharacteristicRepository $characteristicRepo
    ): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $today = new DateTime('today');
        $characteristic = new Characteristic();

        if (!$user->getCharacteristics()) {
            // First need determination form
            $form = $this->createForm(CharacteristicType::class, $characteristic);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $weight = new WeightHistory;

                /** @var float */
                $tempWeight = $form->getData()->getTempWeight();

                $weight->setUser($user);
                $weight->setWeight($tempWeight);
                $weight->setDate($today);

                $user->addWeight($weight);
                $user->setCharacteristics($characteristic);
                $userRepository->save($user, true);

                $characteristicRepo->save($characteristic, true);
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
            return $this->render('home/index.html.twig', [
                'form' => $form,
            ]);
        }

        // Daily weight form
        $dailyWeightForm = $this->createForm(WeightHistoryType::class);
        $dailyWeightForm->handleRequest($request);
        if ($dailyWeightForm->isSubmitted() && $dailyWeightForm->isValid()) {
            $existingWeightHisto = $weightHistoRepo->findOneBy(['user' => $user, 'date' => $today]);
            if ($existingWeightHisto) {
                $existingWeightHisto->setWeight($dailyWeightForm->getData('weight')->getWeight());
                $weightHistoRepo->save($existingWeightHisto, true);
            } else {
                $weightHistory = new WeightHistory();
                $weightHistory->setWeight($dailyWeightForm->getData('weight')->getWeight());
                $weightHistory->setDate($today);
                $weightHistory->setUser($user);

                $weightHistoRepo->save($weightHistory, true);
            }
        }

        // setup the daily follow chart
        $caloryLeft = $consumptionCalc->getCaloryLeft();
        switch ($user->getCharacteristics()->getGoal()) {
            case User::GAIN_OBJECTIVE:
                $caloriesChart = $chartJS->gainCaloriesUserChart($user);
                break;
            case User::LEAN_OBJECTIVE:
                $caloriesChart = $chartJS->leanCaloriesUserChart($user);
                break;
            default:
                $caloriesChart = $chartJS->maintenanceCaloriesUserChart($user);

                //Macro charts
                $proteinChart = $chartJS->proteinUserChart($user);
                $lipidChart = $chartJS->lipidUserChart($user);
                $carbChart = $chartJS->carbUserChart($user);
        }

        return $this->render('home/index.html.twig', [
            'dailyWeightForm' => $dailyWeightForm,
            'proteinChart' => $proteinChart,
            'lipidChart' => $lipidChart,
            'carbChart' => $carbChart,
            'caloriesChart' => $caloriesChart,
            'caloryLeft' => $caloryLeft,
            'weightEvolutionChart' => $chartJS->weightEvolution($user)
        ]);
    }
}
