<?php

namespace App\Controller\Bank;

use App\Entity\Emprunt;
use App\Services\Bank\EmpruntService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

        if($demande->getIsAccepted()) {

            $this->addFlash('error', 'Cette demande a déjà été acceptée');
            return $this->redirectToRoute('gestion_banque');

        } elseif($demande->getIsReimbursed()) {

            $this->addFlash('error', 'Cette demande a déjà été acceptée');
            return $this->redirectToRoute('gestion_banque');

        } else {
            $user = $demande->getUser();
            $demande
                ->setIsAccepted(true)
                ->setAcceptedBy($this->getUser()->getUsername())
            ;

            $user->setMoney($user->getMoney() + $demande->getMontant());
            $entityManager->persist($demande);
            $entityManager->flush();

            $this->addFlash('success', 'La demande de prêt a bien été acceptée');
            return $this->redirectToRoute('gestion_banque');

        }
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

        $user = $emprunt->getUser();

        /**
         * Errors case
         */
        if(!$emprunt->getIsAccepted()) {

            $this->addFlash('error', "L'emprunt n'a pas encore été accordé. Vous devez accorder l'emprunt avant de le valider.");
            return $this->redirectToRoute('gestion_banque');

        } elseif($emprunt->getIsReimbursed()) {

            $this->addFlash('error', "Cet emprunt a déjà été remboursé. Vous ne pouvez pas faire cela");
            return $this->redirectToRoute('gestion_banque');

        } else {

            $emprunt
                ->setIsReimbursed(true)
                ->setValidateBy($this->getUser()->getUsername())
            ;

            $entityManager->persist($emprunt);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'La demande de prêt a bien été acceptée');
            return $this->redirectToRoute('gestion_banque');
        }

    }
}
