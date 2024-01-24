<?php

namespace App\Controller\Api;

use App\Repository\ReviewRepository;
use App\Entity\Review;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;


/**
 * @Route("/api/", name="app_api_reviews_")
 */
class ReviewController extends AbstractController {

    private $userRepository;
    private $mediaRepository;
    private $reviewRepository;
    private $entityManager;
    // Injection des services 
    public function __construct(UserRepository $userRepository, MediaRepository $mediaRepository, ReviewRepository $reviewRepository, EntityManagerInterface $entityManager) {
        $this->userRepository = $userRepository;
        $this->mediaRepository = $mediaRepository;
        $this->reviewRepository = $reviewRepository;
        $this->entityManager = $entityManager;
    }
    /**
     * Add a review
     * @Route("reviews/add", name="list", methods={"POST"})
     */

    public function addReview(Request $request) {
        // get forms data
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'];
        $mediaId = $data['mediaId'];

        $user = $this->userRepository->find($userId);

        // check if user & media exists
        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }

        $media = $this->mediaRepository->find($mediaId);

        if (!$media) {
            return $this->json([
                'message' => "Cette oeuvre n\'existe pas",
            ], 409);
        }

        // check if user didnt already review this media
        if ($this->reviewRepository->findBy(['user_review' => $user, 'media' => $media])) {
            return $this->json([
                'message' => 'Vous avez déjà laissé un avis sur cette oeuvre',
            ], 409);
        }

        // create & hydrate the review
        $review = new Review();
        $review->setUserReview($user);
        $review->setMedia($media);
        $review->setTitle($data['title']);
        $review->setContent($data['content']);
        $review->setRating($data['rating']);
        $review->setIsValidated(0);
        $review->setCreatedAt(new DateTimeImmutable());
        // send to db
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        // return review data to front with 201 status
        return $this->json([
            'message' => 'Votre critique a bien été ajoutée',
            'id' => $review->getId(),
            'title' => $review->getTitle(),
            'content' => $review->getContent(),
            'rating' => $review->getRating(),
        ], 201);
    }

    /**
     * remove a review
     * @Route("reviews/delete", name="delete", methods={"POST"})
     */

    public function delete(Request $request) {

        $data = json_decode($request->getContent(), true);
        $reviewId = $data['reviewId'];
        $userId = $data['userId'];

        $review = $this->reviewRepository->find($reviewId);

        $user = $this->userRepository->find($userId);


        if (!$review) {
            return $this->json([
                'message' => 'Cette critique n\'existe pas',
            ], 409);
        }

        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }


        // you can delete a review if you wrote it OR if you are an admin
        if ($review->getUserReview()->getId() != $userId && (!in_array('ROLE_ADMIN', $user->getRoles()))) {
            return $this->json([
                'message' => 'Vous n\'êtes pas l\'auteur de cette critique',
            ], 409);
        }

        $this->entityManager->remove($review);
        $this->entityManager->flush();


        return $this->json([
            'message' => 'Votre critique a bien été supprimée',
        ], 200);
    }

    /**
     * update a review
     * @Route("reviews/update", name="update", methods={"POST"})
     */

    public function udpate(Request $request) {

        $data = json_decode($request->getContent(), true);
        $reviewId = $data['reviewId'];
        $userId = $data['userId'];
        $review = $this->reviewRepository->find($reviewId);
        $user = $this->userRepository->find($userId);


        if (!$review) {
            return $this->json([
                'message' => 'Cette critique n\'existe pas',
            ], 409);
        }

        if (!$user) {
            return $this->json([
                'message' => 'Cet utilisateur n\'existe pas',
            ], 409);
        }


        if ($review->getUserReview()->getId() != $userId) {
            return $this->json([
                'message' => 'Vous n\'êtes pas l\'auteur de cette critique',
            ], 409);
        }


        $review->setTitle($data['title']);
        $review->setContent($data['content']);
        $review->setRating($data['rating']);
        $review->setUpdatedAt(new DateTimeImmutable());

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Votre critique a bien été modifiée',
            'id' => $review->getId(),
            'title' => $review->getTitle(),
            'content' => $review->getContent(),
            'rating' => $review->getRating(),
        ], 200);
    }
}
