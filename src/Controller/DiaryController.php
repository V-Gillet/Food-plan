<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Service\ChartJS;
use App\Repository\MealRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/diary')]
class DiaryController extends AbstractController
{
    public const MEAL_LIMIT = 20;

    #[Route('', name: 'app_diary')]
    public function index(
        MealRepository $mealRepository,
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $meals = $mealRepository->findBy([], ['date' => 'DESC'], self::MEAL_LIMIT);

        return $this->render(
            'diary/index.html.twig',
            [
                'meals' => $meals,

            ]
        );
    }

    #[Route('/{meal}', name: 'app_meal_show')]
    public function show(
        Meal $meal,
        ChartJS $chartJS
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        return $this->render(
            'diary/show.html.twig',
            [
                'meal' => $meal,
                'lipidChart' => $chartJS->lipidMealChart($meal),
                'proteinChart' => $chartJS->proteinMealChart($meal),
                'carbChart' => $chartJS->carbMealChart($meal),

            ]
        );
    }

    #[Route('/nouveau', name: 'app_meal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MealRepository $mealRepository): Response
    {
        $meal = new Meal();
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mealRepository->save($meal, true);

            return $this->redirectToRoute('app_diary', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meal/new.html.twig', [
            'meal' => $meal,
            'form' => $form,
        ]);
    }

    #[Route('/{meal}/modifier', name: 'app_meal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meal $meal, MealRepository $mealRepository): Response
    {
        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mealRepository->save($meal, true);

            return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('meal/edit.html.twig', [
            'meal' => $meal,
            'form' => $form,
        ]);
    }

    #[Route('/{meal}', name: 'app_meal_delete', methods: ['POST'])]
    public function delete(Request $request, Meal $meal, MealRepository $mealRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $meal->getId(), $request->request->get('_token'))) {
            $mealRepository->remove($meal, true);
        }

        return $this->redirectToRoute('app_meal_index', [], Response::HTTP_SEE_OTHER);
    }
}
