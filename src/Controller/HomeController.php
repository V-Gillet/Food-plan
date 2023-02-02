<?php

namespace App\Controller;

use DateTime;
use App\Entity\Need;
use App\Service\ChartJS;
use App\Entity\WeightHistory;
use App\Service\NeedsCalculator;
use App\Form\UserInformationsType;
use App\Form\WeightHistoryType;
use App\Repository\NeedRepository;
use App\Repository\UserRepository;
use App\Repository\WeightHistoryRepository;
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
        ComsumptionCalculator $consumptionCalc,
        WeightHistoryRepository $weightHistoRepo
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $today = new DateTime('today');

        // First need determination form
        $form = $this->createForm(UserInformationsType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $weight = new WeightHistory;

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
