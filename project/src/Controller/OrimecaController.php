<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class OrimecaController extends AbstractController
{
    private $entityManager;
    private $storeId = 1;
    private $store;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->store = $this->entityManager->getRepository(Store::class)->find($this->storeId);
    }

    // Récupérer la liste des produits
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

    // Récupérer la liste des catégories
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

    // Récupérer un produit par son id
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

    // Récupérer les produits par catégorie
    #[Route('/api/orimeca/category/{id}', name: 'api_products_by_category', methods: ['GET'])]
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

    // Récupérer les produits les plus vendus   
    #[Route('/api/orimeca/products/top', name: 'api_products_top', methods: ['GET'])]
    public function getTopProducts(SerializerInterface $serializer): JsonResponse
    {
        $products = $this->entityManager->getRepository(Product::class)->findTopProductsByStoreId($this->storeId, 6);
        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders']
        ];
        $data = $serializer->serialize($products, JsonEncoder::FORMAT, $context);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    // Récupérer les produits les plus vendus   
    #[Route('/api/orimeca/cart/{slug}', name: 'api_cart_products', methods: ['GET'])]
    public function getCartProducts(SerializerInterface $serializer, Request $request): JsonResponse
    {
        $ids = $request->get('slug');
        
        $elements = explode(',', $ids);
    
        // Tableau pour stocker les nombres entiers positifs
        $nombresEntiers = [];
        
        // Parcourir chaque élément
        foreach ($elements as $element) {
            // Vérifier si l'élément est un nombre entier, positif et non nul
            if (is_numeric($element) && intval($element) == $element && $element > 0) {
                $nombresEntiers[] = intval($element);
            }
        }

        if (empty($nombresEntiers)) {
            return new JsonResponse(['error' => 'No products found'], Response::HTTP_NOT_FOUND);
        }
        
        $products = [];

        foreach ($nombresEntiers as $nombreEntier) {
            $product = $this->entityManager->getRepository(Product::class)->findOneProductByStoreId($nombreEntier, $this->storeId);
            if ($product) {
                $products[] = $product;
            }
        }

        $context = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['orders']
        ];
        $data = $serializer->serialize($products, JsonEncoder::FORMAT, $context);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

}