<?php

namespace App\Controller\Api;

use App\Service\MediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api/", name="app_api_shows_")
 */
class ShowController extends AbstractController {

    private $mediaService;

    public function __construct(MediaService $mediaService) {
        $this->mediaService = $mediaService;
    }

    /**
     * @Route("shows/{id}", name="read", methods="GET")
     */
    public function read($id): JsonResponse {


        $show = $this->mediaService->getOneMedia($id);

        if ($show == null) {
            $errorMessage = [
                'message' => "Show not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $this->json($show, Response::HTTP_OK, [], ["groups" => 'shows_find']);
    }

    /**
     * @Route("shows", name="list")
     */
    public function list(): JsonResponse {
        $shows = $this->mediaService->getAllMedias('serie');

        return $this->json($shows, 200, [], ["groups" => 'shows']);
    }
}
