<?php

namespace App\Controller\Back;

use App\Entity\Genre;
use DateTimeImmutable;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/genres/", name="backoffice_genres_")
 */
class GenreAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(GenreRepository $genreRepository): Response {

        $genres = $genreRepository->findAll();

        return $this->render('back/genres/list.html.twig', [
            'genres' => $genres,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $genre = new Genre();

        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $genre->setCreatedAt($createdAt);

            $this->em->persist($genre);
            $this->em->flush();


            $this->addFlash('success', 'Genre ajouté');
            return $this->redirectToRoute('backoffice_genres_list');
        }
        return $this->renderForm('back/genres/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id, GenreRepository $genreRepository): Response {

        $genre = $genreRepository->find($id);

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $genre->setUpdatedAt($updatedAt);

            $this->em->persist($genre);
            $this->em->flush();

            $this->addFlash('success', 'Le genre a bien été modifié');
            return $this->redirectToRoute('backoffice_genres_list');
        }

        return $this->renderForm('back/genres/update.html.twig', [
            'form' => $form,
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, GenreRepository $genreRepository): Response {

        $genre = $genreRepository->find($id);

        $genreRepository->remove($genre, true);

        $this->addFlash('success', 'Genre supprimé');

        return $this->redirectToRoute('backoffice_genres_list', [], Response::HTTP_SEE_OTHER);
    }
}
