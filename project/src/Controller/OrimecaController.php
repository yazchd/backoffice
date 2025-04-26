<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class OrimecaController extends AbstractController
{
    private $entityManager;
    private $storeId = 1;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/orimeca/products', name: 'api_products', methods: ['GET'])]
    public function getAllProducts(SerializerInterface $serializer): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findProductsByStoreId($this->storeId);
        // Configurer les options pour éviter les références circulaires
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders', 'shop', 'category']
        ];

        // Sérialiser les produits en JSON
        $data = $serializer->serialize($products, JsonEncoder::FORMAT, $context);
        
        // Retourner une réponse JSON
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/orimeca/categories', name: 'api_categories', methods: ['GET'])]
    public function getCategories(SerializerInterface $serializer): JsonResponse
    {
        $categories = $this->entityManager->getRepository(Category::class)->findCategoriesByStoreId($this->storeId);
        // Configurer les options pour éviter les références circulaires
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders', 'shop', 'category']
        ];

        // Sérialiser les produits en JSON
        $data = $serializer->serialize($categories, JsonEncoder::FORMAT, $context);
        
        // Retourner une réponse JSON
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
    
    #[Route('/api/orimeca/product/{id}', name: 'api_product_show', methods: ['GET'])]
    public function getProduct(int $id, SerializerInterface $serializer): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneProductByStoreId($id, $this->storeId);
        
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        
        // Configurer les options pour éviter les références circulaires
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders']
        ];
        
        // Inclure les informations de shop et category
        $data = $serializer->serialize($product, JsonEncoder::FORMAT, $context);
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/orimeca/products/category/{id}', name: 'api_products_by_category', methods: ['GET'])]
    public function getProductsByCategory(int $id, SerializerInterface $serializer): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findProductsByCategoryId($id); 
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders']
        ];
        $data = $serializer->serialize($products, JsonEncoder::FORMAT, $context);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/orimeca/product/{id}/category/{categoryId}', name: 'api_product_by_category', methods: ['GET'])]
    public function getProductByCategory(int $id, int $categoryId, SerializerInterface $serializer): JsonResponse
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneProductByCategoryId($id, $categoryId);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders']
        ];
        $data = $serializer->serialize($product, JsonEncoder::FORMAT, $context);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}
