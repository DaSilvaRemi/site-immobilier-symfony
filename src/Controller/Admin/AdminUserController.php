<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserAdminType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class AdminUserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    /**
     * @param UserRepository $userRepository
     * @param ObjectManager $manager
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserRepository $userRepository, ObjectManager $manager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/users/index", name="admin.users.index")
     * @return Response
     */
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
            'admin_menu' => 'admin.users.index',
        ]);
    }

    /**
     * @Route("/admin/users/create", name="admin.users.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response{
        $user = new User();
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $user->setPasswordHashed($user->getPlainPassword(), $this->userPasswordHasher);
            $this->manager->persist($user);
            $this->manager->flush();
            $this->addFlash('success', 'Utilisateur crée avec succès');
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('admin/users/create.html.twig', [
            'user' => $user,
            'admin_menu' => 'admin.users.create',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/users/edit/{id}", name="admin.users.edit")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request): Response{
        $form = $this->createForm(UserAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $tmpUser = $form->getData();
            var_dump($tmpUser);

            if($tmpUser->getPlainPassword() !== null){
                $user->setPasswordHashed($tmpUser->getPlainPassword(), $this->userPasswordHasher);
            }

            $this->manager->flush();
            $this->addFlash('success', 'Utilisateur édité avec succès');
            return $this->redirectToRoute('admin.users.index');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/users/delete/{id}", name="admin.users.delete", methods={"GET", "POST", "DELETE"})
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function delete(User $user, Request $request): Response{
        if($request->get('_method') == "DELETE" && $this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))){
            $this->manager->remove($user);
            $this->manager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }

        return $this->redirectToRoute('admin.users.index');
    }

}