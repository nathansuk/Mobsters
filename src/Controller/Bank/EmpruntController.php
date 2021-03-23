<?php

namespace App\Controller\Bank;

use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpruntController extends AbstractController
{
    /**
     * @param UserService $userService
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/emprunt", name="emprunt")
     */
    public function index(UserService $userService, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }

        $emprunt = new Emprunt();
        $askEmpruntForm = $this->createForm(EmpruntType::class);
        $askEmpruntForm->handleRequest($request);

        if($askEmpruntForm->isSubmitted() && $askEmpruntForm->isValid()) {

            $username = $this->getUser()->getUsername();
            $user = $userService->getUserByUsername($username);

            $montant = $askEmpruntForm->get('montant')->getData();
            $motif = $askEmpruntForm->get('motif')->getData();
            $emprunt->setUser($user)
                ->setMontant($montant)
                ->setMotif($motif)
                ->setIsAccepted(false)
                //TO-DO: Le taux sera configurable par les administrateurs.
                ->setInterets(0.0);


            $entityManager->persist($emprunt);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande a bien été envoyée. Consultez cette page régulièrement pour suivre son état.');
            return $this->redirectToRoute('emprunt');

        }

        if($askEmpruntForm->isSubmitted() && !$askEmpruntForm->isValid()){

            $this->addFlash('error', 'Le formulaire est invalide !');
            return $this->redirectToRoute('emprunt');

        }

        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'EmpruntController',
            'askEmpruntForm' => $askEmpruntForm->createView()
        ]);
    }
}
