<?php

namespace App\Controller\Back;

use App\Entity\Media;
use App\Form\BookType;
use DateTimeImmutable;
use App\Service\MediaService;
use App\Repository\MediaRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/books/", name="backoffice_books_")
 */
class BookAdminController extends AbstractController {

    private $mediaService;
    private $mediaRepository;
    private $em;

    public function __construct(MediaService $mediaService, MediaRepository $mediaRepository, EntityManagerInterface $em) {

        $this->mediaService = $mediaService;
        $this->mediaRepository = $mediaRepository;
        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(): Response {

        $books = $this->mediaService->getAllMedias('livre');

        return $this->render('back/books/list.html.twig', [
            'books' => $books,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request, CategoryRepository $categoryRepository): Response {

        $media = new Media();
        // get the right category
        $categoryId = $this->mediaRepository->getCategoryId('livre');
        $category = $categoryRepository->find($categoryId);

        $form = $this->createForm(BookType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $media->setCreatedAt($createdAt);

            $media->setCategory($category);

            $this->em->persist($media);
            $this->em->flush();

            $this->addFlash('success', 'Livre ajouté');
            return $this->redirectToRoute('backoffice_books_list');
        }
        return $this->renderForm('back/books/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id): Response {

        $book = $this->mediaRepository->find($id);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $book->setUpdatedAt($updatedAt);

            $this->em->persist($book);
            $this->em->flush();

            $this->addFlash('success', 'Le livre a bien été modifié');
            return $this->redirectToRoute('backoffice_books_list');
        }

        return $this->renderForm('back/books/update.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET", "POST"})
     */
    public function delete($id): Response {

        $book = $this->mediaRepository->find($id);

        $this->mediaRepository->remove($book, true);

        $this->addFlash('success', 'Livre supprimée');

        return $this->redirectToRoute('backoffice_books_list', [], Response::HTTP_SEE_OTHER);
    }
}
