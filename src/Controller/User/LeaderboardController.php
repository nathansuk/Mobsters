<?php

namespace App\Controller\User;

use App\Services\LeaderboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    /**
     * @param LeaderboardService $leaderboardService
     * @return Response
     * @Route("/classement", name="leaderboard")
     */
    public function index(LeaderboardService $leaderboardService): Response
    {
        /**
         * On recupere le leaderboard en passant $max en argument (nombre de personnes affichées dans le leaderboard)
         */
        $leaderboard = $leaderboardService->getRichestUsers(20);

        return $this->render('leaderboard/index.html.twig', [
            'controller_name' => 'LeaderboardController',
            'leaderboard' => $leaderboard
        ]);
    }
}
