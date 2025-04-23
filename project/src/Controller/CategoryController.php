<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category.index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'category.create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category->setCreatedAt(new \DateTimeImmutable());
            $category->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category created successfully');
            return $this->redirectToRoute('category.index');
        }
        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}', name: 'category.show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(Category::class)->find($id);
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category/{id}/edit', name: 'category.edit', methods: ['GET', 'POST'])]  
    public function edit(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category->setUpdatedAt(new \DateTimeImmutable());

            $em->flush();

            $this->addFlash('success', 'Category updated successfully');
            return $this->redirectToRoute('category.index');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/category/{id}/delete', name: 'category.delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {   

        $category = $em->getRepository(Category::class)->find($id);

        $em->remove($category);
        $em->flush();

        $this->addFlash('success', 'Category deleted successfully');
        return $this->redirectToRoute('category.index');
    }

}
