<?php

namespace App\Controller\Back;

use App\Service\MediaService;
use App\Repository\ActorRepository;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use App\Repository\CharacterRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController {

    private $mediaService;
    private $categoryRepository;
    private $actorRepository;
    private $characterRepository;

    public function __construct(MediaService $mediaService, CategoryRepository $categoryRepository, ActorRepository $actorRepository, CharacterRepository $characterRepository, AuthorRepository $authorRepository) {
        $this->mediaService = $mediaService;
        $this->categoryRepository = $categoryRepository;
        $this->actorRepository = $actorRepository;
        $this->characterRepository = $characterRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/backoffice", name="app_backoffice")
     */
    public function index(): Response {

        // get all medias from the database for the home backoffice page
        $shows = $this->mediaService->getAllMedias('serie');
        $movies = $this->mediaService->getAllMedias('film');
        $animes = $this->mediaService->getAllMedias('anime');
        $mangas = $this->mediaService->getAllMedias('manga');
        $videoGames = $this->mediaService->getAllMedias('jeu video');
        $books = $this->mediaService->getAllMedias('livre');

        $actors = $this->actorRepository->findAll();
        $characters = $this->characterRepository->findAll();
        $authors = $this->authorRepository->findAll();

        return $this->render('back/main/index.html.twig', [
            'movies' => $movies,
            'shows' => $shows,
            'animes' => $animes,
            'mangas' => $mangas,
            'videogames' => $videoGames,
            'books' => $books,
            'actors' => $actors,
            'characters' => $characters,
            'authors' => $authors,
        ]);
    }
}
