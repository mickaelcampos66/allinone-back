<?php

namespace App\Controller\Api;

use App\Service\MediaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="app_api_books_")
 */
class BookController extends AbstractController {

    private $mediaService;
    public function __construct(MediaService $mediaService) {
        $this->mediaService = $mediaService;
    }

    /**
     * @Route("books/{id}", name="read", methods="GET")
     */
    public function read($id): JsonResponse {

        $book = $this->mediaService->getOneMedia($id);

        if ($book == null) {
            $errorMessage = [
                'message' => "Book not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        return $this->json($book, Response::HTTP_OK, [], ["groups" => 'books_find']);
    }

    /**
     * @Route("books", name="list", methods="GET")
     */
    public function list(): JsonResponse {
        $books = $this->mediaService->getAllMedias('livre');

        return $this->json($books, 200, [], ["groups" => 'books']);
    }
}
