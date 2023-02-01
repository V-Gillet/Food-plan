<?php

namespace App\Controller;

use DateTime;
use App\Entity\WeightHistory;
use App\Form\UserInformationsType;
use App\Repository\UserRepository;
use App\Repository\WeightHistoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        UserRepository $userRepository
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

            return $this->redirectToRoute('app_home');
        }
        return $this->render('home/index.html.twig', [
            'form' => $form,
        ]);
    }
}
