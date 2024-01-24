# Carnet de Bord AllinOne

## 19/10/2023 - Sprint 0 - Jour 1

Premier jour de notre apothéose : - Première discussion de groupe et présentation rapide de tout le monde - mise en place du Trello - définition des rôles de l'équipe

Pas d'informations individuelles. On se lance sur le cahier des charges demain. Nom définitif du projet à définir également.

## 20/10/2023 - Sprint 0 - Jour 2

Conception du cahier des charges complet (avec des potentielles modifications futures) comprenant la présentation du projet, ses fonctionnalités (MVP / évolutions), ses spécificités et sa conception. Pour finir nous avons consigné les rôles individuels de l'équipe qu'on avait défini la veille.

Nous avons brièvement commencé le MCD.

## 23/10/2023 - Sprint 0 - Jour 3

Objectif MCD / MLD / Dico données aujourd'hui.

## 24/10/2023 - Sprint 0 - Jour 4

Wireframes à faire aujourd'hui suite au premier rendez vous de suivi avec nos tuteur.ices.
Nous avons rencontrés des difficultés hier quant à la conception du MCD. On partait initialement sur une entité/table par catégorie de média (film/série/anime etc) mais cela rend le MCD trop complexe voire illisible. On sera finalement sous réserve de validation sur une entité principale Media en relation avec une entité Category.

## 25/10/2023 - Sprint 0 - Jour 5

Objectif finir les wireframes pour la fin de ce sprint 0. Bien avancé hier. Maxence s'est occupé de la page d'accueil, Cléo de la page "liste/catégories", Mickael de la page détails d'un media et Yannick des pages profil/favoris.

# 27/10/2023 - Sprint 1 - Jour 1

Installation des projets à faire côté front ou back

## 30/10/2023 - Sprint 1 - Jour 2

Maxence :

- Mise en place dernier jour du header en mobile first (avec menu burger), aujourd'hui desktop + nav des liens
- Difficultés sur l'installation de Tailwind

Cleo :

- Début de mise en place de la page liste, continue aujourd'hui
- Grosses difficultés vis à vis de problèmes informatiques (versions de node etc, corrigé avec Patrice)

Mickael :

- installation projet et CRUD MovieController, OK Read/Find/Delete
- Difficultés sur méthode Create avec le déserializer (transformer requête en objet Media) + circular reference, réglée avec Normalizer

Yannick :

- même que Mickael pour CRUD ShowController
- Mêmes difficultés, + installation pour pouvoir coder sur machine hôte et non VM O'clock

## 31/10/2023 - Sprint 1 - Jour 3

TeamBack : des difficultés pour récupérer les données de l'entité character lors des méthodes List/Read du CRUD. Après une matinée de recherche on a pu debug : le mot character est réservé en SQL donc il faut ajouter une annotation spécifique à l'entité Character sur symfony. On a pu réaliser tous les controllers de nos 6 catégories d'oeuvres suite à cela. Mickael s'est penché sur le début du backoffice ainsi que la consommation de l'API depuis Twig.

TeamFront : Migration de Tailwind à Bootstrap suite à des complications d'utilisation. Modification du header en conséquences et mise en place en cours des caroussels de la page d'accueil par Maxence.
Cleo a rencontré des difficultés sur la mise en place de la page liste, possible pair programming aujourd'hui pour repartir sur de bonnes bases.

## 02/11/2023 - Sprint 1 - Jour 4

TeamBack : Refactorisation de la logique métier CRUD API/backoffice avec la construction d'un MediaService + injection de dépendances par Yannick. Mickael a bien avancé sur le backoffice. Aujourd'hui voir pour commencer à remplir la BDD notamment

TeamFront : Maxence a finit la page accueil, Pair programming a été fait avec Cléo et Yannick pour réexpliquer et créer la structure/les composants de la page List. A continuer aujourd'hui, Maxence va attaquer la page Details

## 03/11/2023 - Sprint 1 - Jour 5

TeamBack : Remplissage de la bdd avec 2 films par ans entre 2000 et 2023 + genres/authors/characters etc. Réflexion avec Mickael autour des actors dans la bdd. Déploiement sur VM oclock de Mickael. Fonctionne mais avec index.php (voir config apache ?). Probleme de CORS au moment des premiers fetchs avec le front, tentative rapide d'installation de nelmiocors qui a arrêté le fonctionnement du serveur. Git revert et suppression de nelmio pour l'instant pour la sprint review.

TeamFront : Page Details bien commencée, comme dit ci-dessus toujours des données bruts pour l'instant sans requête API BDD. Page List ? pas de retour de Cleo sur son avancement suite à soucis persos.

## 06/11/2023 - Sprint 2 - Jour 1

TeamBack : Mickael a quasiment finalisé le backoffice sur son week-end. Aujourd'hui gestion des CORS/HTTPS (pour fonctionner avec Front Vercel également en HTTPS). Commencer également la gestion User/login/authentification

TeamFront : Retour de Cleo, 50% +- de la page List. On a défini les objectifs du sprint 2. Test des données dynamiques de l'API dès que les CORS sont gérés en back

## 07/11/2023 - Sprint 2 - Jour 2

TeamBack : Mise en place de l'authentification, manque le token JWT à réaliser

TeamFront : Maxence finalise la page details et va faire la page/form de connexion.

## 08/11/2023 - Sprint 2 - Jour 3

TeamBack : Authentification ok, gestion de la deconnexion du backoffice via rerouting vers route deconnexion front. token JWT ok. ACL ok sauf bug sur page login. Refactorisation des controllers API CRUD à faire.

TeamFront : Base de la connexion ok, registration en cours.

## 09/11/2023 - Sprint 2 - Jour 4

TeamBack : difficultés pour lire le .htaccess et éviter le index.php en prod -> allowOverride All dans la config apache2. Modification du design du back-office par Mickael. Controllers et gestion des reviews/favoris Yannick. Backend fini hors bugfixes.

TeamFront : Page liste Cléo réalisée, registration et login/logout également Maxence.

## 10/11/2023 - Sprint 2 - Jour 5

TeamBack : légères améliorations et bugfixes
TeamFront : Corrections de bugs, gestion de la navbar, lien vers backoffice seulement si roles ok. Début gestion review

## 13/11/2023 - Sprint 3 - Jour 1

TeamBack : corrections mineures du CSS du backoffice, amélioration de la route GET /${id} pour transmettre username de l'auteur des reviews.
TeamFront : mise en page de la page details, ajout de la feature review sur une oeuvre par un utilisateur connecté. Reste à faire page profile/favori si le temps

## 14/11/2023 - Sprint 3 - Jour 2

TeamBack : Mise en place de la modération des reviews, et commencement barre de recherche, par Mickael
TeamFront : Pages CGU/mentions légales + page profile + footer. Finitions design page details d'une oeuvre

## 15/11/2023 - Sprint 3 - Jour 3

TeamBack : Mickael a finalisé la barre de recherche, correction ACL/controllers pour l'ajout d'une review. On ne parvient pas à rendre utilisable la route avec une restriction des rôles. Public access pour l'instant pour garantir son fonctionnement. Vu pour retirer le debugger de symfony en prod (posait soucis d'erreurs 500 par le passé).
TeamFront : Page details finalisée, modifications mineures et clean code.

## 16/11/2023 - Sprint 3 - Jour 4

TeamBack : commentaires sur le code + fixbug sur la modification des credentials user
TeamFront : fix bugs layout/commentaires sur le code
