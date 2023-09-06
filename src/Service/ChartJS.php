<?php

namespace App\Service;

use App\Entity\Meal;
use App\Entity\User;
use App\Repository\WeightHistoryRepository;
use DateInterval;
use DateTime;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartJS
{

    public function __construct(
        private ChartBuilderInterface   $chartBuilder,
        private ComsumptionCalculator   $consumptionCalc,
        private WeightHistoryRepository $weightHistoRepo
    )
    {
    }

    public function proteinUserChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#F4B357',
                        '#EBEBEB'
                    ],
                    'data' => [$user->getNeed()->getProtein(), ($user->getNeed()->getProtein() + $user->getNeed()->getLipid() + $user->getNeed()->getCarb())],
                ],
            ],
        ]);

        return $chart;
    }

    public function lipidUserChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#6CB6E2',
                        '#EBEBEB'
                    ],
                    'data' => [$user->getNeed()->getLipid(), ($user->getNeed()->getProtein() + $user->getNeed()->getLipid() + $user->getNeed()->getCarb())],
                ],
            ],
        ]);

        return $chart;
    }

    public function carbUserChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#CA332F',
                        '#EBEBEB'
                    ],
                    'data' => [$user->getNeed()->getCarb(), ($user->getNeed()->getProtein() + $user->getNeed()->getLipid() + $user->getNeed()->getCarb())],
                ],
            ],
        ]);

        return $chart;
    }

    public function gainCaloriesUserChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        if ($this->consumptionCalc->getCaloryLeft() < 0) {
            $caloryLeft = 0;
            $bgColor = '#CA332F';
        } else {
            $bgColor = '#EBEBEB';
            $caloryLeft = $this->consumptionCalc->getCaloryLeft();
        }

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        $bgColor,
                        '#45DB2E',

                    ],
                    'data' => [$this->consumptionCalc->totalCaloryConsummed(), $caloryLeft],
                ],
            ],
        ]);

        return $chart;
    }

    public function leanCaloriesUserChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        if ($this->consumptionCalc->getCaloryLeft() < 0) {
            $bgColor = '#CA332F';
            $caloryLeft = 0;
        } else {
            $bgColor = '#EBEBEB';
            $caloryLeft = $this->consumptionCalc->getCaloryLeft();
        }

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        $bgColor,
                        '#45DB2E',
                    ],
                    'data' => [$this->consumptionCalc->totalCaloryConsummed(), $caloryLeft],
                ],
            ],
        ]);

        return $chart;
    }

    public function maintenanceCaloriesUserChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        if ($this->consumptionCalc->getCaloryLeft() < 0) {
            $caloryLeft = 0;
            $bgColor = '#CA332F';
        } else {
            $bgColor = '#EBEBEB';
            $caloryLeft = $this->consumptionCalc->getCaloryLeft();
        }

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        $bgColor,
                        '#45DB2E',
                    ],
                    'data' => [$this->consumptionCalc->totalCaloryConsummed(), $caloryLeft],
                ],
            ],
        ]);

        return $chart;
    }

    public function proteinMealChart(Meal $meal): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#F4B357',
                        '#EBEBEB'
                    ],
                    'data' => [$meal->getProtein(), ($meal->getProtein() + $meal->getLipid() + $meal->getCarb())],
                ],
            ],
        ]);

        return $chart;
    }

    public function lipidMealChart(Meal $meal): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#6CB6E2',
                        '#EBEBEB'
                    ],
                    'data' => [$meal->getLipid(), ($meal->getProtein() + $meal->getLipid() + $meal->getCarb())],
                ],
            ],
        ]);

        return $chart;
    }

    public function carbMealChart(Meal $meal): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#CA332F',
                        '#EBEBEB'
                    ],
                    'data' => [$meal->getCarb(), ($meal->getProtein() + $meal->getLipid() + $meal->getCarb())],
                ],
            ],
        ]);

        return $chart;
    }

    public function weightEvolution(User $user): ?Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        for ($i = 6; $i >= 0; $i--) {
            $dateinterval = new DateInterval('P' . $i . 'D');
            $today = new DateTime('today');

            /** @var DateTime */
            $weightDate = $today->sub($dateinterval);
            $weight = $this->weightHistoRepo->findOneBy(['user' => $user, 'date' => $weightDate]);
            if (!$weight) {
                $weightsHistos[] = 0;
            } else {
                $weightsHistos[] = $weight->getWeight();
            }
        }

            $chart->setData([
                'datasets' => [
                    [
                        'label' => 'Évolution de votre poids sur 7 jours glissants',
                        'backgroundColor' => [
                            '#45DB2E',
                        ],
                        'data' => [
                            $weightsHistos[0],
                            $weightsHistos[1],
                            $weightsHistos[2],
                            $weightsHistos[3],
                            $weightsHistos[4],
                            $weightsHistos[5],
                            $weightsHistos[6]
                        ],
                    ],
                ],
                'labels' => ['1er jour', '2ème jour', '3ème jour', '4ème jour', 'Avant-Hier', 'Hier', 'Aujourd\'hui'],
            ]);

            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 50,
                        'suggestedMax' => 100,
                    ],
                ],
            ]);
            return $chart;
    }
}
