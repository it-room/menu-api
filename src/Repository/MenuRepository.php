<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    public function findByCategory(string $category): array
    {
        return $this->findBy(['category' => $category], ['id' => 'ASC']);
    }
    
    public function findMenuByUser(int $userId): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.userlink = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findMenuByIngrediant(string $titre): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.ingrediants', 'i')
            ->andWhere('LOWER(i.titre) LIKE LOWER(:titre)')
            ->setParameter('titre', '%'.$titre.'%')
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Menu[] Returns an array of Menu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Menu
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
