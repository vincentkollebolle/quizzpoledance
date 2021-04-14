Quizz pole dance 


What is Quizz pole Dance ? 
===
You would have guess it, it is a pole dance application which allow you to create quizz ! 

Comment installer le projet ? 
====
- Git clone du proejt
- Composer update
- Configure .env.local file in order to match your local database
- php bin/console doctrine:migration:migrate 
- php bin/console doctrine:fixtures:load

Compte administrator 
====
Pour configurer l'email de connexion pour pouvoir gérer questions et réponses.
login : toto@toto.com 
mdp: tototo

