<?php

namespace App\Controller\Api;

use App\Service\MediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_videogames_")
 */
class VideogameController extends AbstractController {

    private $mediaService;
    // injection du service
    public function __construct(MediaService $mediaService) {
        $this->mediaService = $mediaService;
    }

    /**
     * @Route("videogames/{id}", name="read", methods="GET")
     */
    public function read($id): JsonResponse {

        $videogames = $this->mediaService->getOneMedia($id);

        if ($videogames == null) {
            $errorMessage = [
                'message' => "Videogame not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $this->json($videogames, Response::HTTP_OK, [], ["groups" => 'videogames_find']);
    }

    /**
     * @Route("videogames", name="list", methods="GET")
     */
    public function list(): JsonResponse {
        $videogames = $this->mediaService->getAllMedias('jeu video');

        return $this->json($videogames, 200, [], ["groups" => 'videogames']);
    }
}
