<?php

namespace App\Controller\User;

use App\CityApi;
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
    public function show(string $username, UserService $userService): Response
    {
        // TODO CALL THE API HERE

        $user = $userService->getUserByUsername($username);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user
        ]);
    }
}
