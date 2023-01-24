<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\ReservationType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/produit/creer', name: 'produit_creation')]
    public function creer(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $produit = new Produit();
        $formulaire = $this->createForm(ProduitType::class, $produit);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            
            // gestion de l'image
            $image = $formulaire['image']->getData();
            if (!empty($image)) {
                $nomFichier = time() . '.' . $image->guessExtension();
                $image->move($this->getParameter('image_dir'), $nomFichier);
                $produit->setImage($nomFichier);
            }

            // envoi en base de données
            $manager = $managerRegistry->getManager();
            $manager->persist($produit);
            $manager->flush();

            $this->addFlash('success', 'Le produit a bien été créé');
            // return new Exception('À faire : rediriger vers la page d\'accueil');
            return $this->redirectToRoute('produit_creation');
        }

        return $this->render('produit/form.html.twig', [
            'formulaire' => $formulaire->createView()
        ]);
    }

    #[Route('/produits', name: 'produits')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll()
        ]);
    }

    #[Route('/produit/{id}', name: 'produit_detail')]
    public function detail(Produit $produit, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $formulaire = $this->createForm(ReservationType::class);
        $formulaire->handleRequest($request);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $produit->setReservationText($formulaire['reservationText']->getData());
            $manager = $managerRegistry->getManager();
            $manager->persist($produit);
            $manager->flush();
            $this->addFlash('success', 'Le produit a bien été réservé');
            return $this->redirectToRoute('produit_detail', ['id' => $produit->getId()]);
        }

        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'formulaire' => $formulaire->createView()
        ]);
    }
}
