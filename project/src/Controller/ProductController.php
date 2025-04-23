<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProductController extends AbstractController
{
    #[Route('/product', name: 'product.index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();


        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
    #[Route('/product/create', name: 'product.create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->uploadThumbnail($product, $form);

            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product added successfully');
            return $this->redirectToRoute('product.show', ['id' => $product->getId()]);
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}/edit', name: 'product.edit', methods: ['GET', 'POST'])]
    public function edit(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $product = $em->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUpdatedAt(new \DateTimeImmutable());

            $this->deleteThumbnail($product);
            
            $this->uploadThumbnail($product, $form);

            $em->flush();
            $this->addFlash('success', 'Product updated successfully');

            return $this->redirectToRoute('product.show', ['id' => $id]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}/delete', name: 'product.delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);

        $this->deleteThumbnail($product);

        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product deleted successfully');

        return $this->redirectToRoute('product.index');
    }   

    #[Route('/product/{id}', name: 'product.show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);


        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    private function uploadThumbnail(Product $product, FormInterface $form): void
    {
        $thumbnailFile = $form->get('thumbnailFile')->getData();
        if (!$thumbnailFile) {
            return;
        }

        $fileName = uniqid().'.'. $thumbnailFile->guessExtension();

        $thumbnailFile->move(
            $this->getParameter('kernel.project_dir') . '/public/uploads/images/product',
            $fileName
        );

        $product->setThumbnail($fileName);
    }

    private function deleteThumbnail(Product $product): void
    {
        $thumbnail = $product->getThumbnail();

        // dd($thumbnail, $this->getParameter('kernel.project_dir') . '/public/uploads/images/product/' . $thumbnail);
        if ($thumbnail) {
            unlink($this->getParameter('kernel.project_dir') . '/public/uploads/images/product/' . $thumbnail);
        }
    }
}
