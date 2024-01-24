<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/backoffice/users/", name="backoffice_users_")
 */
class UserAdminController extends AbstractController {

    private $passwordHasher;
    private $em;
    private $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, EntityManagerInterface $em) {

        $this->passwordHasher = $passwordHasher;
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    /**
     * get all users
     * @Route("list", name="list")
     */
    public function list(): Response {

        $users = $this->userRepository->findAll();

        return $this->render('back/users/list.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * get a user
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $user->setCreatedAt($createdAt);

            // hash password
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success',  'Utilisateur ajouté');
            return $this->redirectToRoute('backoffice_users_list');
        }
        return $this->renderForm('back/users/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * update a user
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $user = $this->userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $user->setUpdatedAt($updatedAt);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
            return $this->redirectToRoute('backoffice_users_list');
        }

        return $this->renderForm('back/users/update.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET", "POST"})
     */

    public function delete($id): Response {

        $user = $this->userRepository->find($id);

        $this->userRepository->remove($user, true);

        $this->addFlash('success', 'Utilisateur supprimé');

        return $this->redirectToRoute('backoffice_users_list', [], Response::HTTP_SEE_OTHER);
    }
}
