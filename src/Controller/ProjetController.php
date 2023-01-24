<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjetController extends AbstractController
{
    #[Route('/projet', name: 'projet')]
    public function index(): Response
    {
        return $this->render('projet/index.html.twig');
    }

    #[Route('/admin/projets', name: 'admin_projets')]
    public function adminIndex(ProjetRepository $projetRepository): Response
    {

        return $this->render('projet/adminProjet.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }
    #[Route('/admin/projet/create', name: 'projet_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry): Response
    {
        //Importer entity projet = class en POO

        //Créer une catégorie
        $projet = new Projet();
        //Création d'un formulaire avec en paramètre la nouvelle catégorie
        $form = $this->createForm(ProjetType::class, $projet);


        $form->handleRequest($request); //gestionnaire de requêtes HTTP

        if ($form->isSubmitted() && $form->isValid()) {
            //img
            // vérifier qu'il y a une image, renommer l'image, définir l'image de ma catégorie, upload de l'img da,s me système de fichiers
            //récupère les données du champ img du formulaire.
            $infoImg = $form['img']->getData();
            // si le champ n'est pas vide
            if (!empty($infoImg)) {
                //récupère l'extension de fichier (le format de l'image)
                $extensionImg = $infoImg->guessExtension();
                // crée un nom de fichier unique à partir d'un timestamp
                $nomImg = time() . '.' . $extensionImg;
                //télécharge le fichier dans le dossier adéquat
                $projet->setImg($nomImg);
                //télécharge le fichier dans le dossier adéquat (config/services.yaml)
                $infoImg->move($this->getParameter('projet_img_dir'), $nomImg);
            }
            //Récupère le gestionnaire
            $manager = $managerRegistry->getManager();
            //précise au gestionnaire qu'on va vouloir envoyer un objet en bdd (le rend persistant / liste d'attente)
            $manager->persist($projet);
            //envoie les objets persistés en bdd, Envoyer en base de données
            $manager->flush();
            //message de succès
            $this->addFlash('success', 'Le projet a bien été crée');
            //redirection
            return $this->redirectToRoute('admin_projets');
        }

        //Redirection
        return $this->render('projet/create.html.twig', [
            'projetForm' => $form->createView()
        ]);
    }


    #[Route('/admin/projet/update/{id}', name: 'projet_update')]
    public function update(Projet $projet, Request $request, ManagerRegistry $managerRegistry): Response
    {

        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData();

            if ($infoImg !== null) {

                $oldImg = $this->getParameter('projet_img_dir') . '/' . $projet->getImg();

                if ($projet->getImg() !== null && file_exists($oldImg)) {
                    unlink($oldImg);
                }
                //même traitement qu'en haut
                $extensionImg = $infoImg->guessExtension();
                $nomImg = time() . '.' . $extensionImg;
                $projet->setImg($nomImg);
                $infoImg->move($this->getParameter('projet_img_dir'), $nomImg);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($projet);
            $manager->flush();

            $this->addFlash('success', 'Le projet est modifié !');

            return $this->redirectToRoute('admin_projets');
        }


        return $this->render('projet/update.html.twig', [
            'projetForm' => $form->createView()
        ]);
    }

    #[Route('/admin/projet/delete/{id}', name: 'projet_delete')]
    public function delete(Projet $projet, ManagerRegistry $managerRegistry): Response
    {
        $img = $this->getParameter('projet_img_dir') . '/' . $projet->getImg();
        if ($projet->getImg() !== null && file_exists($img)) {
            unlink($img);
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($projet);
        $manager->flush();
        // message de succès
        $this->addFlash('danger', 'Supprimé');
        return $this->redirectToRoute('admin_projets');
    }
}
