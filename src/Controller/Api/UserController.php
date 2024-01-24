<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MediaRepository;


/**
 * @Route("/api/", name="app_api_users_")
 */
class UserController extends AbstractController {

    private $userRepository;
    private $mediaRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, MediaRepository $mediaRepository, EntityManagerInterface $entityManager) {
        $this->userRepository = $userRepository;
        $this->mediaRepository = $mediaRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * create user
     * @Route("register", name="registration", methods={"POST"})
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository) {

        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];
        $username = $data['username'];

        if (strlen($password) < 6) {
            return $this->json([
                'message' => 'Le mot de passe doit contenir au moins 6 caractères',
            ], 409);
        }

        if ($userRepository->findBy(['email' => $email])) {
            return $this->json([
                'message' => 'Cet email est déjà utilisé',
            ], 409);
        }
        // get all users and check if username is already taken
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            if ($user->getUsername() === $username) {
                return $this->json([
                    'message' => 'Ce pseudo est déjà utilisé',
                ], 409);
            }
        }


        $user = new User();
        $user->setEmail($email);
        // hash password
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setUsername($username);
        $user->setRoles(['ROLE_USER']);

        $createdAt = new DateTimeImmutable();
        $user->setCreatedAt($createdAt);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Merci pour votre inscription',
            'userId' => $user->getId(),
            'userEmail' => $user->getUserIdentifier(),
            'username' => $user->getUsername()
        ], 200);
    }

    /**
     * get all favs of a user
     * @Route("users/favorites/{id}", name="favorites", methods={"GET"}) 
     */

    public function list($id) {

        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }

        // get favoris of user
        $favorites = $user->getMedias();

        return $this->json([
            'favorites' => $favorites,
        ], 200, [], ["groups" => 'movies', 'shows', 'books', 'videogames', 'animes', 'mangas']);
    }

    /**
     * @Route("users/favorites/add", name="add_favorite", methods={"POST"})
     */

    public function addToFavorites(Request $request) {

        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $mediaId = $data['mediaId'];

        $user = $this->userRepository->find($userId);


        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }

        $media = $this->mediaRepository->find($mediaId);

        if (!$media) {
            return $this->json([
                'message' => "Cette oeuvre n\'existe pas",
            ], 409);
        }

        // check if media isnt already in user's favorites, loop instead of in_array because of Collection type. 
        // User->addMedia() already handles Collections but this allows us to send an error msg to front
        $alreadyInFavorites = false;

        foreach ($user->getMedias() as $favorite) {
            if ($favorite->getId() == $mediaId) {
                $alreadyInFavorites = true;
            }
        }
        if ($alreadyInFavorites) {
            return $this->json([
                'message' => 'Vous avez déjà ajouté cette oeuvre à vos favoris',
            ], 409);
        }

        $user->addMedia($media);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'L\'oeuvre a bien été ajoutée à vos favoris',
        ], 201);
    }

    /**
     * remove a media from favorites
     * @Route("users/favorites/delete", name="delete_favorite", methods={"POST"})
     */

    public function removeFromFavorites(Request $request) {

        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $mediaId = $data['mediaId'];

        $user = $this->userRepository->find($userId);


        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }

        $media = $this->mediaRepository->find($mediaId);

        if (!$media) {
            return $this->json([
                'message' => "Cette oeuvre n\'existe pas",
            ], 409);
        }

        // check if media is in user's favorites
        $alreadyInFavorites = false;
        foreach ($user->getMedias() as $favorite) {
            if ($favorite->getId() == $mediaId) {
                $alreadyInFavorites = true;
            }
        }
        if (!$alreadyInFavorites) {
            return $this->json([
                'message' => 'Vous n\'avez pas ajouté cette oeuvre à vos favoris',
            ], 409);
        }

        $user->removeMedia($media);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'L\'oeuvre a bien été supprimée de vos favoris',
        ], 201);
    }

    /**
     * update user credentials
     * @Route("users/update", name="update", methods={"POST"})
     */
    public function updateUserCredentials(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository) {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $newEmail = $data['email'];
        $newUsername = $data['username'];
        $newPassword = $data['password'] ? $data['password'] : null;

        $user = $this->userRepository->find($userId);
        $actualEmail = $user->getEmail();
        $actualUsername = $user->getUsername();

        if ($newPassword !== null) {
            $newHashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($newHashedPassword);
        }

        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }

        // check if email or username already exist

        if ($newEmail !== $user->getEmail() && $userRepository->findBy(['email' => $newEmail])) {
            return $this->json([
                'message' => 'Cet email est déjà utilisé',
            ], 409);
        }

        $users = $userRepository->findAll();

        foreach ($users as $user) {
            if ($user->getUsername() === $newUsername) {
                return $this->json([
                    'message' => 'Ce pseudo est déjà utilisé',
                ], 409);
            }
        }

        if ($newEmail !== $actualEmail) {
            $user->setEmail($newEmail);
        }

        if ($newUsername !== $actualUsername) {
            $user->setUsername($newUsername);
        }


        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Vos informations ont bien été mises à jour',
        ], 200);
    }
}
