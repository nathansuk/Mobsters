<?php

namespace App\Controller;

use App\Entity\News;
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
     * @return Response
     * @Route("/", name="home")
     */
    public function index(LeaderboardService $leaderboardService, MissionService $missionService): Response
    {
        $lastNews = $this->getDoctrine()->getRepository(News::class)->findBy(array(), ['createdAt' => 'DESC']);

        $richestUser = $leaderboardService->getRichestUsers(1);
        $bestMissionUser = $leaderboardService->getBestMissionUser(1);
        $missions = $missionService->getAvailableMission();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'Accueil',
            'lastNews' => $lastNews,
            'richestUser' => $richestUser,
            'bestMissionUser' => $bestMissionUser,
            'missions' => $missions
        ]);
    }
}
