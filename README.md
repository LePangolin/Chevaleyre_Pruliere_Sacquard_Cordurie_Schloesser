# Atelier n°1

Fichier d'insertion de la base de données dans /app/database/seeders/inserts.sql

## GROUPE

    - Chevaleyre Antoine
    - Pruliere Justine
    - Schloesser Adrien
    - Cordurié Lucas
    - Sacquard Julien
    
## Mise en route

```Cloner le repository
docker-compose up -d
docker-compose exec --workdir /app php /bin/bash
composer install
php vendor/bin/doctrine orm:schema-tool:create```

## Objectifs

    - Crée une application web de galerie d'images
    - Fonctionnement et stabilité de l'application, respect du cahier des charge,
    - Qualité de conception: modèles, architecture des composants, motivation des choix
    techniques,
    - Qualité et sécurité du code: optimisation charge serveur / des requêtes de l'ORM, protection
    des failles,
    - Mise en oeuvre du travail d’équipe: répartition des tâches, utilisation de git.
    - Liste des fonctionnalités réalisées

## Fonctionnalités Obligatoires

<h3>Affichage d’une galerie :</h3>

- [x] Affichage d’informations relatives à la galerie : titre, nombre photos, nom du créateur,date de création, mots-clés associés à la galerie

- [x] Affichage des photos sous forme de vignettes, les tailles relatives de chaque vignette devrontvarier (de façon aléatoire ou en fonction d’un critère à définir)

- [ ] Pagination lorsque le nombre d’images est trop important,

<h3>Affichage d’une image :</h3>

- [x] Maximiser la taille d’affichage

- [x] Affichage des informations relatives à l’image : titre, date de création, mots-clés associés à l’image

<h3>Création de galeries, ajout d’images : </h3>

- [x] Création d’une galerie : nom, description, mots-clés,

- [x] Mode d’accès : public (tous les visiteurs peuvent visualiser), privé (seuls les utilisateurs d’une liste peuvent visualiser)

- [x] Dépôt d’une image : titre, mots-clés (ils peuvent être suggérés à partir des mots-clés de la galerie ou des images déjà déposées), données techniques (par exemple, exif)

- [x] Les données descriptives d’une galerie sont modifiables à tout moment par son propriétaire, y compris la liste des utilisateurs autorisés

- [x] Le propriétaire d’une galerie peut ajouter des photos à tout moment.

<h3>Affichage des listes de galeries : </h3>
<h4>Pour les utilisateur non connecter : </h4>

- [x] Affichage des galeries publiques

- [x] Une image au hasard prise dans la galerie, sous forme de vignette, pour chaque galerie

- [x] Pagination lorsque la liste est trop longue

<h4>Pour les utilisateurs connectés (en plus des non connecter) : </h4>

- [x] Liste des galeries publiques et privées auxquelles l’utilisateur a accès

## Fonctionnalités optionnelles

- [x] Recherches sur les mots clés : la recherche peut porter sur les galeries, ou sur l’ensemble des images. Dans ce cas, seules les images présentes dans une galerie accessible à l’utilisateur sont incluses dans le résultat. On peut combiner plusieurs mots clés.

- [ ] Galeries partagées : en plus des galeries publiques (visibles par tous les visiteurs) et des galeries privées (visibles par les utilisateurs déclarés uniquement), les galeries partagées sont des galeries pour lesquelles une liste d’utilisateurs autorisés à contribuer et fournie. Tous les utilisateurs dans cette liste peuvent ajouter des images. Cette liste peut être différente de la
      liste des utilisateurs autorisés à visualiser la galerie.

- [ ] Commentaires sur les images : les utilisateurs authentifiés peuvent ajouter des commentaires sur les images des galeries auxquelles ils ont accès

- [ ] Navigation dans une galerie : possibilité de passer d’une image à la suivante sans remonter à la galerie.

- [ ] Gestion de profil : chaque utilisateur peut définir et gérer des informations dans son profil. Ces informations peuvent être affichées avec les galeries dont il est propriétaire.

- [ ] Gestion de groupes : dans son profil, un utilisateur peut définir des groupes d’utilisateurs enregistrés. Ces groupes sont utilisés lors de la création de galeries privées ou partagées, pour donner rapidement des droits d’accès ou de contribution à des ensemble d’utilisateurs.
