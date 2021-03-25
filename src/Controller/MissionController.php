<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\User;
use App\Entity\UserMission;
use App\Services\Missions\MissionService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionController extends AbstractController
{
    /**
     * @param MissionService $missionService
     * @return Response
     * @Route("/mission", name="mission")
     */
    public function index(MissionService $missionService): Response
    {
        $missionDispo = $missionService->getAvailableMission();

        return $this->render('mission/index.html.twig', [
            'controller_name' => 'MissionController',
            'missionDispo' => $missionDispo
        ]);
    }

    /**
     * @Route("/mission/accept/{id}", name="accept_mission")
     * @param int $id
     * @param UserService $userService
     * @param MissionService $missionService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function acceptMission(int $id, UserService $userService, MissionService $missionService, EntityManagerInterface $entityManager): Response {

        if(!$this->getUser()) { $this->addFlash('error', "Vous ne pouvez pas faire ça "); }

        /*
         * Here we get the current user and the mission that we want to add
         */

        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        $mission = $missionService->getMissionById($id);

        /*
         * Then, we check by using userAlreadyHasMission function (declared by dependency injection)
         * If the user has already the mission, then return an error, or continue and add in the database.
         */
        $check = $userService->userAlreadyHasMission($user, $mission);

        if(!$check) {
            $userMission = new UserMission();
            $userMission->setUser($this->getUser())
                ->setMission($this->getDoctrine()->getRepository(Mission::class)->find($id))
                ->setDone(false);
            $entityManager->persist($userMission);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'Vous avez déjà accepté cette mission !');
            return $this->redirectToRoute('mission');
        }
        $this->addFlash('success', 'Vous avez accepté la mission');
        return $this->redirectToRoute("mission");
    }

    public function showMissionAccepted(UserService $userService): Response
    {

        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        $userMission = $user->getUserMissions();

        return $this->render('global/overlays/mission_list.html.twig', [
            'user' => $user,
            'userMission' => $userMission
        ]);
    }

}
