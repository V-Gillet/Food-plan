<?php

namespace App\Controller;

use App\Entity\Characteristic;
use DateTime;
use DateInterval;
use App\Entity\Need;
use App\Service\ChartJS;
use App\Entity\WeightHistory;
use App\Form\WeightHistoryType;
use App\Form\CharacteristicType;
use App\Repository\CharacteristicRepository;
use App\Service\NeedsCalculator;
use App\Repository\NeedRepository;
use App\Repository\UserRepository;
use App\Service\ComsumptionCalculator;
use App\Repository\WeightHistoryRepository;
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
        ComsumptionCalculator $consumptionCalc,
        WeightHistoryRepository $weightHistoRepo,
        CharacteristicRepository $characteristicRepo
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $today = new DateTime('today');
        $characteristic = new Characteristic();

        // First need determination form
        $form = $this->createForm(CharacteristicType::class, $characteristic);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO : manage epty historyWeight without setting false datas
            for ($i = 6; $i >= 0; $i--) {
                $now = new DateTime('today');
                $dateinterval = new DateInterval('P' . $i . 'D');
                $weight = new WeightHistory;
                /** @var DateTime */
                $weightDate = $now->sub($dateinterval);
                /** @var float */
                $tempWeight = $form->getData()->getTempWeight();

                $weight->setUser($user);
                $weight->setWeight($tempWeight);
                $weight->setDate($weightDate);

                $user->addWeight($weight);
                $user->setCharacteristics($characteristic);
                $userRepository->save($user, true);
                $characteristicRepo->save($characteristic, true);
            }
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

        $caloryLeft = 0;
        if ($user->getNeed() !== null) {
            $caloryLeft = $consumptionCalc->getCaloryLeft();
            if ($user->getCharacteristics()->getGoal() === 'gain') {
                $caloriesChart = $chartJS->gainCaloriesUserChart($user);
            } elseif ($user->getCharacteristics()->getGoal() === 'lean') {
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
