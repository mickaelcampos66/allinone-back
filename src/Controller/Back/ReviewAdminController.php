<?php

namespace App\Controller\Back;

use App\Entity\Review;
use DateTimeImmutable;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backoffice/reviews/", name="backoffice_reviews_")
 */
class ReviewAdminController extends AbstractController {


    private $em;

    public function __construct(EntityManagerInterface $em) {

        $this->em = $em;
    }

    /**
     * get all reviews
     * @Route("list", name="list")
     */
    public function list(ReviewRepository $reviewRepository): Response {

        $reviews = $reviewRepository->findAll();

        return $this->render('back/reviews/list.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * valide a review
     * @Route("{id}/validate", name="validate", methods={"GET"})
     */

    public function validate($id, ReviewRepository $reviewRepository): Response {

        // Récuperation d'un commentaire par son id
        $review = $reviewRepository->find($id);
        // setter pour valider le commentaire
        $review->setIsValidated('true');

        // Ajout d'un message succes
        $this->addFlash('success', 'Commentaire validé');
        $this->em->persist($review);
        $this->em->flush();

        return $this->redirectToRoute('backoffice_reviews_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * remove a review
     * @Route("{id}/delete", name="delete", methods={"GET"})
     */

    public function delete($id, ReviewRepository $reviewRepository): Response {

        // Récuperation d'un commentaire par son id
        $review = $reviewRepository->find($id);
        // Supression du media via la méthode remove
        $reviewRepository->remove($review, true);
        // Ajout d'un message succes
        $this->addFlash('success', 'Commentaire supprimé');

        return $this->redirectToRoute('backoffice_reviews_list', [], Response::HTTP_SEE_OTHER);
    }
}
