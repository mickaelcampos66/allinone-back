<?php

namespace App\Service;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaService {
    private $entityManager;
    private $mediaRepository;
    private $validator;
    private $serializer;

    // inject services
    public function __construct(EntityManagerInterface $entityManager, MediaRepository $mediaRepository, ValidatorInterface $validator, SerializerInterface $serializer) {
        $this->entityManager = $entityManager;
        $this->mediaRepository = $mediaRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function getAllMedias($category): array {
        // get category id with category name
        $categoryId = $this->mediaRepository->getCategoryId($category);

        // get medias with category id
        $medias = $this->mediaRepository->findByCategory($categoryId);

        return $medias;
    }

    public function getOneMedia($id) {
        $media = $this->mediaRepository->find($id);

        return $media;
    }

    public function createMedia($data) {
        $media = $this->serializer->deserialize($data, Media::class, 'json');

        $errorList = $this->validator->validate($media);
        if (count($errorList) > 0) {
            return null;
        }
        $this->entityManager->persist($media);
        $this->entityManager->flush();
        return $media;
    }

    public function updateMedia($id, $data) {
        $media = $this->mediaRepository->find($id);
        if ($media == null) {
            return 'media not found';
        }
        $updatedMedia = $this->serializer->deserialize($data, Media::class, 'json');
        $errorList = $this->validator->validate($media);
        if (count($errorList) > 0) {
            return null;
        }
        $media->setTitle($updatedMedia->getTitle());
        $media->setUpdatedAt($updatedMedia->getUpdatedAt());

        $this->entityManager->persist($media);
        $this->entityManager->flush();
        return $media;
    }

    public function deleteMedia($id) {
        $media = $this->mediaRepository->find($id);
        if ($media == null) {
            return null;
        }
        $this->entityManager->remove($media);
        $this->entityManager->flush();
        return true;
    }
}
