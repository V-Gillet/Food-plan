<?php

namespace App\Service;

use App\Entity\User;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

class ChartJS
{

    public function __construct(private ChartBuilderInterface $chartBuilder)
    {
    }

    public function proteinChart(User $user): Chart
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

    public function lipidChart(User $user): Chart
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

    public function carbChart(User $user): Chart
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

    public function gainCaloriesChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#45DB2E',
                        '#EBEBEB'
                    ],
                    // put the consummed calories here
                    'data' => [$user->getNeed()->getGainCalory(), 1],
                ],
            ],
        ]);

        return $chart;
    }

    public function leanCaloriesChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#45DB2E',
                        '#EBEBEB'
                    ],
                    // put the consummed calories here
                    'data' => [$user->getNeed()->getLossCalory(), 1],
                ],
            ],
        ]);

        return $chart;
    }

    public function maintenanceCaloriesChart(User $user): Chart
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

        $chart->setData([
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                        '#45DB2E',
                        '#EBEBEB'
                    ],
                    // put the consummed calories here
                    'data' => [$user->getNeed()->getMaintenanceCalory(), 1],
                ],
            ],
        ]);

        return $chart;
    }
}
