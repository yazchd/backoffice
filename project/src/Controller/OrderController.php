<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order', name: 'order.index')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/order/create', name: 'order.create')]
    public function create(): Response
    {
        return $this->render('order/create.html.twig');
    }

    #[Route('/order/{id}', name: 'order.show')]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', ['order' => $order]);
    }

    #[Route('/order/{id}/edit', name: 'order.edit')]
    public function edit(Order $order): Response
    {
        return $this->render('order/edit.html.twig', ['order' => $order]);
    }

    #[Route('/order/{id}/delete', name: 'order.delete')]
    public function delete(Order $order): Response
    {
        return $this->render('order/delete.html.twig', ['order' => $order]);
    }


}   
