<?php

namespace App\Controller;

use App\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    /**
     * @return Response
     * @Route("/mission", name="mission")
     */
    public function index(): Response
    {
        $missionDispo = $this->getDoctrine()->getRepository(Mission::class)->findAll();

        return $this->render('mission/index.html.twig', [
            'controller_name' => 'MissionController',
            'missionDispo' => $missionDispo
        ]);
    }
}
