<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\ORM\EntityManager;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @extends ServiceEntityRepository<Media>
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository {
    private $categoryRepository;
    private $entityManagerInterface;

    public function __construct(ManagerRegistry $registry, CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface) {
        parent::__construct($registry, Media::class);
        $this->categoryRepository = $categoryRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }


    public function add(Media $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Media $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCategory($category): array {
        return $this->createQueryBuilder('m')
            ->andWhere('m.category = :id')
            ->setParameter('id', $category)
            ->getQuery()
            ->getResult();
    }


    public function getCategoryId($category) {
        // retrieve all categories
        $categories = $this->categoryRepository->findAll();

        $categoryId = null;

        // get the id
        for ($i = 0; $i < count($categories); $i++) {
            if ($categories[$i]->getName() === $category) {
                $categoryId = $categories[$i]->getId();
            }
        }
        return $categoryId;
    }

    /**
     * Find a media by keyword
     *
     * @param [type] $keyword
     */
    public function findByKeyword($keyword)
    {
        // if keyword is an array, explode it to string to use in doctrine query
        $keywordAsString = is_array($keyword) ? implode(', ', $keyword) : $keyword;

        return $this->createQueryBuilder('m')
            ->leftjoin('m.authors', 'a')
            ->where('m.title LIKE :keyword OR a.fullname LIKE :keyword')
            ->setParameter('keyword', '%' . $keywordAsString . '%')
            ->getQuery()
            ->getResult();
    }
}
