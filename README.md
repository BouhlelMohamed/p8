<h1>Projet 8 : Améliorez une application existante de ToDo & Co</h1>

Pour télécharger le projet :

<i>  1 -  Cloner le projet </i>

git clone https://github.com/BouhlelMohamed/p8

  2 -    Installer les packages 

composer install

3 - Modifier le fichier .env avec les informations de votre bdd :

DATABASE_URL=mysql: //db_user:db_password@127.0.0.1:3306/db_name

 4 -   Création de la base de données

symfony serve doctrine:database:create

 5 -   Ajouter les tables et les colonnes dans votre bdd :

symfony serve doctrine:migrations:migrate

 6 - Ajouter des fausses données :  (Ce n'est pas obligatoire)
 
symfony serve doctrine:fixtures:load
 
 7 - Lancer les tests : 
php bin/phpunit
