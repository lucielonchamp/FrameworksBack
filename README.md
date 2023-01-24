# ÉVALUATION BACK-END

## 00- MISE EN PLACE

- se rendre dans le dossier MAMP/htdocs (ou wamp64/www ou xampp/htdocs)
- créer un nouveau porjet Symfony
```
symfony new --webapp myshop
```

## 01- BASE DE DONNÉES

- créer le fichier .env.local
- y mettre la configuration de la base de données
- créer la base de données depuis le terminal :
```
symfony console doctrine:database:create
```
- créer l'entité Produit :
```
symfony console make:entity Produit
```
- migration :
```
symfony console make:migration
symfony console doctrine:migrations:migrate
```

## 02- CRÉATION DE PRODUIT

- créer le ProduitController :
```
symfony console make:controller Produit
```
- créer le ProduitType :
```
symfony console make:form Produit
```
- configurer le formulaire
- gérer le formulaire dans le Controller
- gérer l'affichage dans la vue (form.html.twig)

## 03- AFFICHER LES PRODUITS

- chercher les produits en base de données sur la route home
- envoyer les produits à la vue associée
- gérer l'affichage sur la page d'accueil
- ajouter le lien vers tous les produits dans la nav

## 04- AFFICHER ET RÉSERVER UN PRODUIT

- créer la route produits dans ProductController
- créer la vue produit/index.html.twig
- créer la route produit_detail dans ProductController
- créer le formulaire de réservation (non associé à une entité)
- créer la vue produit/detail.html.twig
- ajouter des conditions d'affichage
- ajouter les liens dans la nav
- ajouter les liens vers les dtéails produit dans les vues

## 05- STRUCTURE DU PROJET

- rien à faire, Symfony s'en est chargé

## BONUS
