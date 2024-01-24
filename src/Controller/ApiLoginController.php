<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiLoginController extends AbstractController {


    /**
     * Front office login
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function index(Request $request, UserRepository $userRepository, JWTTokenManagerInterface $JWTTokenManager, UserPasswordHasherInterface $userPasswordHasherInterface): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];
        $user = $userRepository->findOneBy(['email' => $email]);
        // check if user with these credentials exist
        if (null === $user) {
            return $this->json([
                'message' => 'Erreur sur vos identifiants',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$userPasswordHasherInterface->isPasswordValid($user, $password)) {
            return $this->json([
                'message' => 'Erreur sur vos identifiants',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // create JWT token and send it with response
        $token = $JWTTokenManager->create($user);

        return $this->json([
            'message' => 'Bienvenue sur votre compte',
            'userId'  => $user->getId(),
            'userEmail' => $user->getUserIdentifier(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'token' => $token
        ]);
    }
}
