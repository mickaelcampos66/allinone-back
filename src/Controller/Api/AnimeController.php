<?php

namespace App\Controller\Api;

use App\Service\MediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_animes_")
 */
class AnimeController extends AbstractController {

    private $mediaService;
    // inject media service 
    public function __construct(MediaService $mediaService) {
        $this->mediaService = $mediaService;
    }

    /**
     * get one anime by id
     * @Route("animes/{id}", name="read", methods="GET")
     */
    public function read($id): JsonResponse {

        // mediaService récupère un anime via son id
        $anime = $this->mediaService->getOneMedia($id);

        if ($anime == null) {
            $errorMessage = [
                'message' => "Anime not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $this->json($anime, Response::HTTP_OK, [], ["groups" => 'animes_find']);
    }

    /**
     * get all animes 
     * @Route("animes", name="list", methods="GET")
     */
    public function list(): JsonResponse {
        $animes = $this->mediaService->getAllMedias('anime');

        // using groups to avoid circular references
        return $this->json($animes, 200, [], ["groups" => 'animes']);
    }
}
