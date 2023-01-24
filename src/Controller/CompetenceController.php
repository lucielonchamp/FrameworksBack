<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompetenceController extends AbstractController
{
    #[Route('/competence', name: 'competence')]
    public function index(): Response
    {
        return $this->render('competence/index.html.twig');
    }

    #[Route('/admin/competences', name: 'admin_competences')]
    public function adminIndex(CompetenceRepository $competenceRepository): Response
    {

        return $this->render('competence/adminCompetence.html.twig', [
            'competences' => $competenceRepository->findAll(),
        ]);
    }
    #[Route('/admin/competence/create', name: 'competence_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry): Response
    {
        //Importer entity competence = class en POO

        //Créer une catégorie
        $competence = new Competence();
        //Création d'un formulaire avec en paramètre la nouvelle catégorie
        $form = $this->createForm(CompetenceType::class, $competence);


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
                $competence->setImg($nomImg);
                //télécharge le fichier dans le dossier adéquat (config/services.yaml)
                $infoImg->move($this->getParameter('competence_img_dir'), $nomImg);
            }
            //Récupère le gestionnaire
            $manager = $managerRegistry->getManager();
            //précise au gestionnaire qu'on va vouloir envoyer un objet en bdd (le rend persistant / liste d'attente)
            $manager->persist($competence);
            //envoie les objets persistés en bdd, Envoyer en base de données
            $manager->flush();
            //message de succès
            $this->addFlash('success', 'Le compétence a bien été créée');
            //redirection
            return $this->redirectToRoute('admin_competences');
        }

        //Redirection
        return $this->render('competence/create.html.twig', [
            'competenceForm' => $form->createView()
        ]);
    }


    #[Route('/admin/competence/update/{id}', name: 'competence_update')]
    public function update(Competence $competence, Request $request, ManagerRegistry $managerRegistry): Response
    {

        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData();

            if ($infoImg !== null) {

                $oldImg = $this->getParameter('competence_img_dir') . '/' . $competence->getImg();

                if ($competence->getImg() !== null && file_exists($oldImg)) {
                    unlink($oldImg);
                }
                //même traitement qu'en haut
                $extensionImg = $infoImg->guessExtension();
                $nomImg = time() . '.' . $extensionImg;
                $competence->setImg($nomImg);
                $infoImg->move($this->getParameter('competence_img_dir'), $nomImg);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($competence);
            $manager->flush();

            $this->addFlash('success', 'Le compétence est modifiée !');

            return $this->redirectToRoute('admin_competences');
        }


        return $this->render('competence/update.html.twig', [
            'competenceForm' => $form->createView()
        ]);
    }

    #[Route('/admin/competence/delete/{id}', name: 'competence_delete')]
    public function delete(Competence $competence, ManagerRegistry $managerRegistry): Response
    {
        $img = $this->getParameter('competence_img_dir') . '/' . $competence->getImg();
        if ($competence->getImg() !== null && file_exists($img)) {
            unlink($img);
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($competence);
        $manager->flush();
        // message de succès
        $this->addFlash('danger', 'Supprimé');
        return $this->redirectToRoute('admin_competences');
    }
}
