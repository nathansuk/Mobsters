<?php

namespace App\Controller\Bank;

use App\Entity\Emprunt;
use App\Services\Bank\EmpruntService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionBanqueController extends AbstractController
{
    /**
     * @return Response
     * @Route("/gestion-banque", name="gestion_banque")
     */
    public function index(): Response
    {

        $demandeEmprunt = $this->getDoctrine()->getRepository(Emprunt::class)->findBy(['isAccepted' => false]);

        $demandeAcceptee = $this->getDoctrine()->getRepository(Emprunt::class)->findBy([
            'isAccepted' => true,
            'isReimbursed' => false
        ]);

        $empruntsRembourses = $this->getDoctrine()->getRepository(Emprunt::class)->findBy(['isReimbursed' => true]);

        $this->isGranted('ROLE_BANQUIER');

        return $this->render('gestion_banque/index.html.twig', [
            'controller_name' => 'GestionBanqueController',
            'demandes' => $demandeEmprunt,
            'demandes_acceptees' => $demandeAcceptee,
            'empruntsRembourses' => $empruntsRembourses
        ]);
    }

    /**
     * @param int $id
     * @param EmpruntService $empruntService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/gestion-banque/accept/{id}", name="accept_demande")
     */
    public function accepterDemande(int $id, EmpruntService $empruntService, EntityManagerInterface $entityManager): Response {

        $demande = $empruntService->getEmpruntById($id);

        if($demande->getIsAccepted() == true) {
            $this->addFlash('error', 'Cette demande a déjà été acceptée');
            return $this->redirectToRoute('gestion_banque');
        }

        if($demande->getIsReimbursed() == true) {
            $this->addFlash('error', 'Cette demande a déjà été acceptée');
            return $this->redirectToRoute('gestion_banque');
        }

        $demande->setIsAccepted(true);
        $entityManager->persist($demande);
        $entityManager->flush();

        $this->addFlash('success', 'La demande de prêt a bien été acceptée');
        return $this->redirectToRoute('gestion_banque');
    }

    /**
     * @param int $id
     * @param EmpruntService $empruntService
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/gestion-banque/valider/{id}", name="valider_emprunt")
     */
    public function validerEmprunt(int $id, EmpruntService $empruntService, EntityManagerInterface $entityManager): Response {

        $emprunt = $empruntService->getEmpruntById($id);

        if($emprunt->getIsAccepted() == false) {
            $this->addFlash('error', "L'emprunt n'a pas encore été accordé. Vous devez accorder l'emprunt avant de le valider.");
            return $this->redirectToRoute('gestion_banque');
        }

        if($emprunt->getIsReimbursed() == true) {
            $this->addFlash('error', "Cet emprunt a déjà été remboursé. Vous ne pouvez pas faire cela");
            return $this->redirectToRoute('gestion_banque');
        }

        $emprunt->setIsReimbursed(true);
        $entityManager->persist($emprunt);
        $entityManager->flush();

        $this->addFlash('success', 'La demande de prêt a bien été acceptée');
        return $this->redirectToRoute('gestion_banque');
    }
}
