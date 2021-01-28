# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a61d7562d83f403084857c4e067338fd)](https://app.codacy.com/gh/Jpetitgas/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=Jpetitgas/SnowTricks&utm_campaign=Badge_Grade)
[![SymfonyInsight](https://insight.symfony.com/projects/817a96fc-b334-413e-ba03-967dd474542a/big.svg)](https://insight.symfony.com/projects/817a96fc-b334-413e-ba03-967dd474542a)

How to install the project with your own local environment
 
Symfony 5.2
PHP 7.4.9
MySQL 5.7.31
 
Follow each following steps :
 
First clone this repository from your terminal in your preferred project directory.
https://github.com/Jpetitgas/SnowTricks.git
You need to edit the ".env" file:
 - Setup Doctrine for DB connection.
 - MAILER_DSN

you need also to setup the email adresse to allow sending account validation email or use the forgotten password form to send a reset password email for user account.
 
From your terminal, go to the project directory and tape those command line :
- composer install
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load
