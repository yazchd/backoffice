<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Récupère tous les produits d'un magasin spécifique
     * 
     * @param int $storeId L'identifiant du magasin
     * @return Product[] Un tableau de produits
     */
    public function findProductsByStoreId(int $storeId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'p.slug', 'p.description', 'p.thumbnail', 'p.price', 'c.name as categoryName')
            ->leftJoin('p.category', 'c')  
            ->andWhere('p.store = :storeId')
            ->setParameter('storeId', $storeId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Récupère un produit spécifique dans un magasin donné
     * 
     * @param int $productId L'identifiant du produit
     * @param int $storeId L'identifiant du magasin
     * @return Product|null Le produit trouvé ou null si aucun produit ne correspond
     */
    public function findOneProductByStoreId(int $productId, int $storeId): ?Product
    {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'p.slug', 'p.description', 'p.thumbnail', 'p.price', 'c.name as categoryName')
            ->leftJoin('p.category', 'c')
            ->andWhere('p.id = :productId')
            ->andWhere('p.store = :storeId')
            ->setParameter('productId', $productId)
            ->setParameter('storeId', $storeId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findProductsByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'p.slug', 'p.description', 'p.thumbnail', 'p.price', 'c.name as categoryName')
            ->leftJoin('p.category', 'c')
            ->andWhere('p.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneProductByCategoryId(int $productId, int $categoryId): ?Product
    {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'p.slug', 'p.description', 'p.thumbnail', 'p.price', 'c.name as categoryName')
            ->leftJoin('p.category', 'c')
            ->andWhere('p.id = :productId')
            ->andWhere('p.category = :categoryId')
            ->setParameter('productId', $productId)
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
