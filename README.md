[![Codacy Badge](https://app.codacy.com/project/badge/Grade/fc7df169a70c46deb20159c1e5411290)](https://www.codacy.com/gh/BouhlelMohamed/p8/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=BouhlelMohamed/p8&amp;utm_campaign=Badge_Grade)
<h1>Projet 8 : Améliorez une application existante de ToDo & Co :</h1>  

Lien : http://p8.mohamed-bouhlel.com/login

Pour télécharger le projet :

<div class="highlight highlight-source-shell"><pre>1 - Cloner le projet :</pre></div>

git clone https://github.com/BouhlelMohamed/p8

<div class="highlight highlight-source-shell"><pre>2 - Installer les packages :</pre></div>

composer install

<div class="highlight highlight-source-shell"><pre>3 - Modifier le fichier .env avec les informations de votre bdd : </pre></div>

DATABASE_URL=mysql: //db_user:db_password@127.0.0.1:3306/db_name

<div class="highlight highlight-source-shell"><pre>4 - Création de la base de données : </pre></div>

symfony serve doctrine:database:create

<div class="highlight highlight-source-shell"><pre>5 - Ajouter les tables et les colonnes dans votre bdd : </pre></div>

symfony serve doctrine:migrations:migrate

<div class="highlight highlight-source-shell"><pre>6 - Ajouter des fausses données :  (Ce n'est pas obligatoire)</pre></div>

symfony serve doctrine:fixtures:load
 
 <div class="highlight highlight-source-shell"><pre>7 - Lancer les tests : </pre></div>

php bin/phpunit


<hr>
<h1>Comment contribuer au projet :</h1>

Pour bien commencer , suivez les étapes suivantes:

    1 - Cliquer sur Fork le projet tout en haut à droite.
    2 - Vérifier s'il existe des issues ouvertes
    3 - Créer une nouvelle branche avec le prefix < contrib: > et commencer à travailler dessus.
    3 - Ajouter la branche sur le fork .
    4 - Respecter les normes PSR vous pouvez en savoir plus ici https://www.php-fig.org/psr/
    5 - Vérifier que l'ensemble des tests sont ok.
    6 - Créer une nouvelle pull-request 

Merci :)
