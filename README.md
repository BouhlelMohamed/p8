<h1>Projet 8 : Améliorez une application existante de ToDo & Co</h1>

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
