<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Store;
use App\Form\StoreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StoreController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/store', name: 'store.index')]
    public function index(): Response
    {
        $stores = $this->entityManager->getRepository(Store::class)->findAll();

        return $this->render('store/index.html.twig', [
            'stores' => $stores,
        ]);
        
    }

    #[Route('/store/create', name: 'store.create')]
    public function create(Request $request): Response
    {
        $store = new Store();
        $form = $this->createForm(StoreType::class, $store);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $store->setCreatedAt(new \DateTimeImmutable());
            $store->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($store);
            $this->entityManager->flush();

            return $this->redirectToRoute('store.index');
        }

        return $this->render('store/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/store/{id}/categories', name: 'store.categories')]
    public function categories(Store $store): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findBy(['store' => $store]);

        return $this->render('store/categories.html.twig', [
            'store' => $store,
            'categories' => $categories,
        ]);
    }

    #[Route('/store/{id}/products', name: 'store.products')]
    public function products(Store $store): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findBy(['store' => $store]);

        return $this->render('store/products.html.twig', [
            'store' => $store,
            'products' => $products,
        ]);
    }

    #[Route('/store/{id}/edit', name: 'store.edit')]
    public function edit(Store $store): Response
    {
        return $this->render('store/edit.html.twig', [
            'store' => $store,
        ]);
    }

    #[Route('/store/{id}/delete', name: 'store.delete')]
    public function delete(Store $store): Response
    {
        $this->entityManager->remove($store);
        $this->entityManager->flush();

        return $this->redirectToRoute('store.index');
    }

    #[Route('/store/{id}', name: 'store.show')]
    public function show(Store $store): Response
    {   
        $categories = $this->entityManager->getRepository(Category::class)->findBy(['store' => $store]);
        $products = $this->entityManager->getRepository(Product::class)->findBy(['store' => $store]);

        return $this->render('store/show.html.twig', [
            'store' => $store,
            'categories' => $categories,
            'products' => $products,
        ]);
    }

}
