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
     * @param EmpruntService $empruntService
     * @return Response
     * @Route("/gestion-banque", name="gestion_banque")
     */
    public function index(EmpruntService $empruntService): Response
    {
        $demandeEmprunt = $empruntService->getPendingDemands();
        $demandeAcceptee = $empruntService->getAcceptedDemands();
        $empruntsRembourses = $empruntService->getReimbursedDemands();

        $this->isGranted('ROLE_BANQUIER');

        return $this->render('gestion_banque/index.html.twig', [
            'controller_name' => 'Espace banquier',
            'demandes' => $demandeEmprunt,
            'demandes_acceptees' => $demandeAcceptee,
            'empruntsRembourses' => $empruntsRembourses
        ]);
    }

    /**
     * @param int $id
     * @param EmpruntService $empruntService
     * @return Response
     * @Route("/gestion-banque/accept/{id}", name="accept_demande")
     */
    public function accepterDemande(int $id, EmpruntService $empruntService): Response {

        $demande = $empruntService->getEmpruntById($id);

        if($demande->getIsAccepted() || $demande->getIsReimbursed()) {
            $this->addFlash('error', 'Cet emprunt a déjà été acceptée ou remboursée');
            return $this->redirectToRoute('gestion_banque');
        }

        $empruntService->acceptDemand($demande, $this->getUser()->getUsername());

        $this->addFlash('success', 'La demande de prêt a bien été acceptée');
        return $this->redirectToRoute('gestion_banque');
    }

    /**
     * @param int $id
     * @param EmpruntService $empruntService
     * @return Response
     * @Route("/gestion-banque/valider/{id}", name="valider_emprunt")
     */
    public function validerEmprunt(int $id, EmpruntService $empruntService): Response {

        $emprunt = $empruntService->getEmpruntById($id);

        if(!$emprunt->getIsAccepted() || $emprunt->getIsReimbursed()) {
            $this->addFlash('error', "L'emprunt n'a pas encore été accordé ou a déjà été remboursé.");
            return $this->redirectToRoute('gestion_banque');
        }

        $empruntService->setEmpruntAsReimbursed($emprunt, $this->getUser()->getUsername());
        $this->addFlash('success', 'La demande de prêt a bien été acceptée');
        return $this->redirectToRoute('gestion_banque');

    }
}
