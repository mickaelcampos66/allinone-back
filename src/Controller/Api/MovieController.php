<?php

namespace App\Controller\Api;

use App\Service\MediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api/", name="app_api_movies_")
 */
class MovieController extends AbstractController {

    private $mediaService;
    public function __construct(MediaService $mediaService) {
        $this->mediaService = $mediaService;
    }

    /**
     * @Route("movies/{id}", name="read", methods="GET")
     */
    public function read($id): JsonResponse {

        $movie = $this->mediaService->getOneMedia($id);

        if ($movie == null) {
            $errorMessage = [
                'message' => "Movie not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $this->json($movie, Response::HTTP_OK, [], ["groups" => 'movies_find']);
    }

    /**
     * @Route("movies", name="list", methods="GET")
     */
    public function list(): JsonResponse {

        $movies = $this->mediaService->getAllMedias('film');
        return $this->json($movies, 200, [], ["groups" => 'movies']);
    }
}
