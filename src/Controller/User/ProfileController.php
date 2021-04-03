<?php

namespace App\Controller\User;

use App\CityApi;
use App\Entity\UserMission;
use App\Services\Missions\MissionService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @param string $username
     * @param UserService $userService
     * @return Response
     * @Route("/profil/{username}", name="user_profile")
     */
    public function show(string $username, UserService $userService, MissionService $missionService): Response
    {
        $user = $userService->getUserByUsername($username);

        if(!$user){
            $this->addFlash('error', "Cet utilisateur n'existe pas");
            return $this->redirectToRoute("home");
        }

        $api = new CityApi($username);

        $cityUser = array(
          'diamonds' => $api->getDiamonds(),
          'badges' => $api->getListBadge(),
          'rooms' => $api->getRooms(),
          'groups' => $api->getListGroupe()
        );

        $missions = $this->getDoctrine()->getRepository(UserMission::class)->findBy([
            'user' => $user,
            'isRewarded' => true
        ]);

        $user = $userService->getUserByUsername($username);
        $userMission = $userService->getUserById($user->getId());

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil',
            'user' => $user,
            'userMission' => $userMission,
            'cityUser' => $cityUser,
            'missions' => $missions
        ]);
    }
}
