<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LoginType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {

        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // $user = new User();
        // $user->setEmail('test@test.com');
        // $user->setPassword($hasher->hashPassword($user, 'password'));
        // $user->setRoles(['ROLE_ADMIN']);
        // $user->setUsername('test');
        
        // $em->persist($user);
        // $em->flush();

        
        $form = $this->createForm(LoginType::class);    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            
            $email = new TemplatedEmail();

            try {
                $email->from($data['email'])
                    ->to($data['email'])
                    ->subject('Test')
                    ->htmlTemplate('emails/login.html.twig')
                    ->context([
                        'data' => $data,
                ]);

                $mailer->send($email);
                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('home');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email');
            }


        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'form' => $form->createView(),
        ]);
    }
}
