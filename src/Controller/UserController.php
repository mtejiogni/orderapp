<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;


final class UserController extends AbstractController {

    #[Route('/user', name: 'user.index')]
    public function index(Request $request, UserRepository $repo): Response {
        return $this->render('user/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }


    #[Route('/user/edit', name: 'user.edit')]
    public function edit(): Response {
        return $this->render('user/edit.html.twig');
    }


    #[Route('/user/register', name: 'user.register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $em): Response {
        // Recuperation de la session dans la requete
        $session = $request->getSession();

        //Initialisation de la variable pour la gestion des erreurs
        $errors= null;

        if($request->isMethod('POST')) {
            $nom= $request->request->get('nom');
            $prenom= $request->request->get('prenom');
            $telephone= $request->request->get('telephone');
            $email= $request->request->get('email');

            if(empty($nom) || empty($prenom) || empty($telephone) || empty($email)) {
                $errors= [
                    "type" => "warning",
                    "message" => "L'un des champs n'est pas renseigné"
                ];

                // Stockage dans la session
                $session->set('errors', $errors);
                //dd($request->getSession()->get('errors'));

                return $this->render('user/edit.html.twig', [
                    'params' => $request->request->all()
                ]);
            }
            else {
                $user = new User();
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setTelephone($telephone);
                $user->setEmail($email);
                $user->setCreatedAt();
                $user->setUpdatedAt();

                $em->persist($user);
                $em->flush();

                $errors= [
                    "type" => "success",
                    "message" => "Utilisateur ajouté avec succès"
                ];
                $session->set('errors', $errors);

                return $this->redirectToRoute('user.index');
            }

            
        }
        else {
            $errors= [
                "type" => "danger",
                "message" => "La méthode d'envoie des données est invalide"
            ];
            $session->set('errors', $errors);

            return $this->render('user/edit.html.twig');
        }

    }
}
