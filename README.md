# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a61d7562d83f403084857c4e067338fd)](https://app.codacy.com/gh/Jpetitgas/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=Jpetitgas/SnowTricks&utm_campaign=Badge_Grade)

How to install the project with your own local environment (like Wampserver for to have database)
- Symfony 5.2 
- PHP 7.4.9 
- MySQL 5.7.31

Follow each following steps :

First clone this repository from your terminal in your preferred project directory. https://github.com/Jpetitgas/SnowTricks.git 

You need to edit the ".env" file:
- Setup Doctrine for DB connection.
- MAILER_DSN

You also need to set up the email address to allow sending account validation email or use the forgotten password form to send a reset password email for the user account.

From your terminal, go to the project directory and tape those command line :
- composer install
- php bin/console doctrine:database:create
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load
- symfony server:start -d

iden : user2021 password: user2021
