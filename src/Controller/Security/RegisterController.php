<?php


namespace App\Controller\Security;


use App\CityApi;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegisterController extends AbstractController
{

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param LoginFormAuthenticator $loginAuthenticator
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @return Response
     */
    public function registration(UserService $userService, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $loginAuthenticator, GuardAuthenticatorHandler $guardAuthenticatorHandler): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $register_form = $this->createForm(RegistrationType::class, $user);
        $register_form->handleRequest($request);

        if($register_form->isSubmitted() && $register_form->isValid()) {

            /*
             * This tries to connect to city's API
             * If the API throw an error, then the user isn't registered on Habbocity
             * Then we redirect and add an error message
             */
            try {
                $api = new CityApi($register_form->get('username')->getData());
                $motto = $api->getMission();
            } catch (\Exception $exception){
                $this->addFlash('error', 'Cet utilisateur ne semble pas exister sur Habbocity');
                return $this->redirectToRoute('register');
            }

            /**
             * We check if the user has the correct moto "CODE-IM-PseudoOnHabbocity"
             * if not we redirect on register and add a error message.
             */

            $checkUsername = $userService->getUserByUsername($register_form->get('username')->getData());

            if($checkUsername !== null){
                $this->addFlash('error', 'Cet utilisateur est déjà inscrit.');
                return $this->redirectToRoute('register');
            }
            if($motto == 'ImperialMobsters') {

                $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash)
                    ->setCreatedAt(new \DateTime("now"))
                    ->setMoney(0)
                    ->setRoles(['ROLE_USER'])
                    ->setFinishedMission(0);

                $entityManager->persist($user);
                $entityManager->flush();
                $guardAuthenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $loginAuthenticator, 'main');
                return $this->redirectToRoute('home');

            } else {
                $this->addFlash('notice', "Attention tu n'as pas mis le code dans ta mission");
                return $this->redirectToRoute("register");
            }
        }


        return $this->render('security/register.html.twig', [
            'controller_name' => 'Inscription',
            'register_form' => $register_form->createView(),
        ]);
    }
}