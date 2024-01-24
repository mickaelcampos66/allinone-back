<?php

namespace App\Controller\Back;

use App\Entity\Author;
use DateTimeImmutable;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/authors/", name="backoffice_authors_")
 */
class AuthorAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * @Route("list", name="list")
     */
    public function list(AuthorRepository $authorRepository): Response {

        $authors = $authorRepository->findAll();

        return $this->render('back/authors/list.html.twig', [
            'authors' => $authors,
        ]);
    }


    /**
     * @Route("add", name="add", methods={"GET", "POST"})
     */

    public function add(Request $request): Response {

        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new DateTimeImmutable();

            $author->setCreatedAt($createdAt);

            $this->em->persist($author);
            $this->em->flush();

            $this->addFlash('success', 'Auteur ajouté');
            return $this->redirectToRoute('backoffice_authors_list');
        }
        return $this->renderForm('back/authors/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("{id}/update", name="update", methods={"GET", "POST"})
     */

    public function update(Request $request, $id, AuthorRepository $authorRepository): Response {

        $author = $authorRepository->find($id);

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $updatedAt = new DateTimeImmutable();
            $author->setUpdatedAt($updatedAt);

            $this->em->persist($author);
            $this->em->flush();

            $this->addFlash('success', 'L\'auteur a bien été modifié');
            return $this->redirectToRoute('backoffice_authors_list');
        }

        return $this->renderForm('back/authors/update.html.twig', [
            'form' => $form,
            'author' => $author,
        ]);
    }

    /**
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, AuthorRepository $authorRepository): Response {

        $author = $authorRepository->find($id);

        $authorRepository->remove($author, true);

        $this->addFlash('success', 'Auteur supprimé');

        return $this->redirectToRoute('backoffice_authors_list', [], Response::HTTP_SEE_OTHER);
    }
}
