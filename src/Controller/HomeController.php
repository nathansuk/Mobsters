<?php

namespace App\Controller;

use App\Entity\News;
use App\Services\LeaderboardService;
use App\Services\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param LeaderboardService $leaderboardService
     * @return Response
     * @Route("/", name="home")
     */
    public function index(LeaderboardService $leaderboardService): Response
    {
        $lastNews = $this->getDoctrine()->getRepository(News::class)->findBy(array(), ['createdAt' => 'DESC']);

        $richestUser = $leaderboardService->getRichestUsers(1);
        $bestMissionUser = $leaderboardService->getBestMissionUser(1);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lastNews' => $lastNews,
            'richestUser' => $richestUser,
            'bestMissionUser' => $bestMissionUser
        ]);
    }
}
