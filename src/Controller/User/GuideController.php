<?php

namespace App\Controller;

use App\Services\LeaderboardService;
use App\Services\Missions\MissionService;
use App\Services\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param LeaderboardService $leaderboardService
     * @param MissionService $missionService
     * @return Response
     * @Route("/", name="home")
     */
    public function index(LeaderboardService $leaderboardService, MissionService $missionService, NewsService $newsService): Response
    {
        $lastNews = $newsService->getAllNews();
        $richestUser = $leaderboardService->getRichestUsers(1);
        $bestMissionUser = $leaderboardService->getBestMissionUser(1);
        $missions = $missionService->getAvailableMission();

        return $this->render('guide/index.html.twig', [
            'controller_name' => 'Guide',
            'lastNews' => $lastNews,
            'richestUser' => $richestUser,
            'bestMissionUser' => $bestMissionUser,
            'missions' => $missions
        ]);
    }
}
