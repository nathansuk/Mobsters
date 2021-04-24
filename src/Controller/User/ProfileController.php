<?php

namespace App\Controller\User;

use App\CityApi;
use App\Services\Missions\MissionService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function show(string $username, UserService $userService): Response
    {
        $user = $userService->getUserByUsername($username);
        $rewardedMission = $userService->getUsersRewardedMission($user);
        $user = $userService->getUserByUsername($username);
        $userMission = $userService->getUserById($user->getId());

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


        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'userMission' => $userMission,
            'cityUser' => $cityUser,
            'missions' => $rewardedMission
        ]);
    }

    /**
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @Route("/search/", name="searchuser")
     */
    public function search(Request $request, UserService $userService): Response
    {
        $user = $userService->getUserByUsername($request->query->get('user'));

        if(!$user){
            $this->addFlash('error', "Cet utilisateur n'existe pas");
            return $this->redirectToRoute("home");
        }

        else{
            return $this->redirectToRoute("user_profile", ['username' => $request->query->get('user')]);
        }

    }
}
