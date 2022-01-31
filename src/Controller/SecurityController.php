<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{


    /**
     * @var ObjectManager
     */
    private $em;
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(ObjectManager $em, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->em = $em;
    }

    /**
     * @Route("/signing", name="signing")
     * @param Request $request
     * @return Response
     */
    public function signing(Request $request): Response{
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setPasswordHashed($user->getPassword(), $this->userPasswordHasher);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'Compte utilisateur créer avec succès !');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/signing.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response{
        $lastUserName = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUserName,
            'error' => $error
        ]);
    }
}