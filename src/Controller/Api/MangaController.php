<?php

namespace App\Controller\Api;

use App\Service\MediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_mangas_")
 */
class MangaController extends AbstractController {

    private $mediaService;
    public function __construct(MediaService $mediaService) {
        $this->mediaService = $mediaService;
    }


    /**
     * @Route("mangas/{id}", name="read", methods="GET")
     */
    public function read($id): JsonResponse {

        $manga = $this->mediaService->getOneMedia($id);

        if ($manga == null) {
            $errorMessage = [
                'message' => "Manga not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $this->json($manga, Response::HTTP_OK, [], ["groups" => 'mangas_find']);
    }

    /**
     * @Route("mangas", name="list", methods="GET")
     */
    public function list(): JsonResponse {
        $mangas = $this->mediaService->getAllMedias('manga');

        return $this->json($mangas, 200, [], ["groups" => 'mangas']);
    }
}
