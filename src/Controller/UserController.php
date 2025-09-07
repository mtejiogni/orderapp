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



    #[Route('/user/edit/{id}', name: 'user.edit', requirements: ['id' => '\d*'], defaults: ['id' => null])]
    public function edit(Request $request, UserRepository $repo): Response {
        $id= $request->get('id');

        if($id == null) {
            return $this->render('user/edit.html.twig');
        }
        else if (is_numeric($id) == false) {
            throw $this->createNotFoundException('Paramètre invalide.');
        }
        else {
            $user = $repo->find((int)$id);
            if ($user == false) {
                throw $this->createNotFoundException('Utilisateur non trouvé.');
            }
            else {
                return $this->render('user/edit.html.twig', [
                    'user' => $user
                ]);
            }
        }
    }




    #[Route('/user/register', name: 'user.register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserRepository $repo, EntityManagerInterface $em): Response {
        // Variable contenant le template à afficher
        $template = null;

        //Initialisation de la variable pour la gestion des erreurs
        $errors= null;
        
        // Recuperation de la session dans la requete
        $session = $request->getSession();

        if($request->isMethod('POST')) { // Si la méthode POST est utilisée
            $id= $request->request->get('id') ?? null;
            $nom= $request->request->get('nom');
            $prenom= $request->request->get('prenom');
            $telephone= $request->request->get('telephone');
            $email= $request->request->get('email');

            if(empty($nom) || empty($prenom) || empty($telephone) || empty($email)) {
                $errors= [
                    "type" => "warning",
                    "message" => "L'un des champs n'est pas renseigné"
                ];
                $template= $this->render('user/edit.html.twig', [
                    'user' => (object) $request->request->all()
                ]);
            }
            else {
                if($id != null && $id != '') { // Modifier un utilisateur
                    $user= $repo->find((int)$id);
                    $user->setNom($nom);
                    $user->setPrenom($prenom);
                    $user->setTelephone($telephone);
                    $user->setEmail($email);
                    $user->setUpdatedAt();
                    $em->persist($user);
                    $em->flush();
                    $errors= [
                        "type" => "success",
                        "message" => "L'utilisateur a été modifié avec succès"
                    ];
                }
                else { // Ajouter un utilisateur
                    $user= new User();
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
                }
                $template= $this->redirectToRoute('user.index');
            }
        }
        else {
            $errors= [
                "type" => "danger",
                "message" => "La méthode d'envoie des données est invalide"
            ];
            $template= $this->render('user/edit.html.twig');
        }


        // Stockage dans la session
        $session->set('errors', $errors);
        //dd($request->getSession()->get('errors'));
        return $template;
    }



    

    #[Route('/user/delete/{id}', name: 'user.delete', requirements: ['id' => '\d*'])]
    public function delete(Request $request, UserRepository $repo, EntityManagerInterface $em): Response {
        // Variable contenant le template à afficher
        $template = $this->redirectToRoute('user.index');

        //Initialisation de la variable pour la gestion des erreurs
        $errors= null;
        
        // Recuperation de la session dans la requete
        $session = $request->getSession();

        $id= $request->get('id');

        if (is_numeric($id) == false) {
            $errors= [
                "type" => "warning",
                "message" => "Paramètre invalide : Impossible de supprimer cette utilisateur"
            ];
        }
        else {
            $user = $repo->find((int)$id);
            if ($user == false) {
                $errors= [
                    "type" => "danger",
                    "message" => "Utilisateur non trouvé."
                ];
            }
            else {
                $em->remove($user);
                $em->flush();
                $errors= [
                    "type" => "success",
                    "message" => "Utilisateur supprimé avec succès."
                ];
            }
        }
        
        // Stockage dans la session
        $session->set('errors', $errors);
        return $template;
    }
}
