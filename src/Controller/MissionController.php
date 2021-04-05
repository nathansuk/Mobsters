<?php

namespace App\Controller;

use App\Entity\Mission;
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
     * @param UserService $userService
     * @param MissionService $missionService
     * @return Response
     * @Route("/mission", name="mission")
     */
    public function index(UserService $userService, MissionService $missionService): Response
    {

        $missionAvailable = $missionService->getAvailableMission();
        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        /*
         *  we create an empty array, where we will put each mission that user doesnt already have
         */
        $missions = array();
        /*
         * For each available mission, check if user didnt got it before, and then push into array.
         */
        foreach ($missionAvailable as $mission){
            if (!$userService->userAlreadyHasMission($user, $mission)){
                array_push($missions, $mission);
            }
        }
        return $this->render('mission/index.html.twig', [
            'controller_name' => 'Les missions',
            'missionAvailable' => $missions
        ]);
    }

    /**
     * @Route("/mission/accept/{id}", name="accept_mission")
     * @param int $id
     * @param UserService $userService
     * @param MissionService $missionService
     * @return Response
     */
    public function acceptMission(int $id, UserService $userService, MissionService $missionService): Response {

        if(!$this->getUser()) { $this->addFlash('error', "Vous ne pouvez pas faire ça "); }

        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        $mission = $missionService->getMissionById($id);

        if($mission == null){
            $this->addFlash('success', 'Il y a eu un probleme');
            return $this->redirectToRoute("mission");
        }

        /*
         * If the mission has is specific to a clan, this check if the user belongs it.
         */
        if($mission->getClan() !== null){
            if($user->getClan() !== $mission->getClan()){
                $this->addFlash('error', 'Vous ne faites pas partie de ce clan');
                return $this->redirectToRoute("mission");
            }
        }

        if(!$userService->userAlreadyHasMission($user, $mission)) {
            $missionService->addUserMission($this->getUser(), $mission);
        } else {
            $this->addFlash('error', 'Vous avez déjà accepté cette mission !');
            return $this->redirectToRoute('mission');
        }
        $this->addFlash('success', 'Vous avez accepté la mission');
        return $this->redirectToRoute("mission");
    }

    /**
     * @Route("/mission/abandon/{id}", name="abandon_mission")
     * @param int $id
     * @param UserService $userService
     * @param MissionService $missionService
     * @return Response
     */
    public function abandonMission(int $id, UserService $userService, MissionService $missionService): Response {

        if(!$this->getUser()) { $this->addFlash('error', "Vous ne pouvez pas faire ça "); }

        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        $mission = $missionService->getMissionById($id);
        $userMissionToDelete = $userService->getUserMissionsById($user, $id);

        if($mission == null || $userMissionToDelete == null){
            $this->addFlash('error', 'Il y a eu un problème');
            return $this->redirectToRoute("mission");
        }

        if($userService->userAlreadyHasMission($user, $mission)) {

            $missionService->removeMission($user, $userMissionToDelete);
            $this->addFlash('success', 'Vous avez abandonné la mission');
            return $this->redirectToRoute("mission");

        } else {
            $this->addFlash('error', 'Vous ne pouvez pas faire cela');
            return $this->redirectToRoute("mission");
        }
    }

    /**
     * @param int $id
     * @param UserService $userService
     * @param MissionService $missionService
     * @return Response
     * @Route("/mission/markasdone/{id}", name="mark_mission")
     */
    public function markMissionAsDone(int $id, UserService $userService, MissionService $missionService): Response
    {
        if(!$this->getUser()) { $this->addFlash('error', "Vous ne pouvez pas faire ça "); }

        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        $mission = $missionService->getMissionById($id);
        $missionToMark = $userService->getUserMissionsById($user, $id);

        if($mission == null || $missionToMark == null){
            $this->addFlash('success', 'Il y a eu un probleme');
            return $this->redirectToRoute("mission");
        }

        if($userService->userAlreadyHasMission($user, $mission)) {

            $missionService->markMissionAsFinished($missionToMark);
            $this->addFlash('success', 'Votre demande a bien été soumise.');
            return $this->redirectToRoute("mission");

        } else {
            $this->addFlash('error', 'Vous ne pouvez pas faire cela');
            return $this->redirectToRoute("mission");
        }
    }

    /*
     * This is used to render mission list on the global/overlay/mission template
     */

    public function showMissionAccepted(UserService $userService): Response {

        $user = $userService->getUserByUsername($this->getUser()->getUsername());
        $userMission = $user->getUserMissions();
        return $this->render('global/overlays/mission_list.html.twig', [
            'userMission' => $userMission,
        ]);
    }

    public function getMissionModule(UserService $userService): Response {

        $user = $userService->getUserByUsername($this->getUser()->getUsername());

        $userMissionNotFinished = $this->getDoctrine()->getRepository(UserMission::class)->findBy(
            [
                'user' => $user,
                'done' => false,
                'isRewarded' => false
            ]);

        return $this->render('global/modules/mission.html.twig', [
            'userMissionNotFinished' => $userMissionNotFinished
        ]);
    }

}
