Quizz pole dance 


What is Quizz pole Dance ? 
===
You would have guess it, it is a pole dance application which allow you to create quizz ! 

Pré-requis 
===
- Git 
- Composer
- MySQL (Disposer d'un compte utilisateur de base de donnée MySQL.)
- PHP 7.4


Comment installer le projet ? 
====
- Cloner le projet via Git 
```
$> git clone https://github.com/vincentkollebolle/quizzpoledance.git
```
- Se rendre dans le dossier du projet 
```
$> cd quizzpoledance/
```
- Installer les dépendances manquantes 
```
$> composer install
```
- Créer et configurer un fichier .env.local et renseigner vos inforamtions de connexion MySQL. 
```
# .env.local
DATABASE_URL="mysql://yourLogin:yourPass@localhost:3306/yourBddName?serverVersion=13&charset=utf8"
```
- Créer la base de donnée
```
php bin/console doctrine:database:create
```

-Créer les migrations 
```
$> php bin/console make:migration
```
-Exécuter les migrations
```
$> php bin/console doctrine:migrations:migrate
```

- Charger les fixutres du projet (pack de question sur les figures de Pole Dance + utilisateur admin.
Login du compte administrateur par défaut: admin@admin.com
Mot de passe par défaut : admin 
```
$> php bin/console doctrine:fixtures:load
```

