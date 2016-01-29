Projeto Base para novos Projetos
=======================

Descrição
------------
Este projeto consiste a criação de um projeto desenvolvido em do Zend Framework 2 com o
ORM Doctrine efetuando a persistencia de arquivos diretamente no banco de dados.

A configuração da máquina utilizada para criação do Projeto

Linux Mint 16
Apache 2.2.22
MySQL 5.5.29
PHP 5.4.6
Git 1.7.10.4

Preparação do ambiente
------------

Obtendo o o Skeleton
----------------------------

    cd /var/www/
    sudo git clone git://github.com/hanunjunior/modelo.git nomeDoProjeto


Instalando dependências
----------------------------
COMPOSER_PROCESS_TIMEOUT=999999999 php composer.phar self-update
COMPOSER_PROCESS_TIMEOUT=999999999 php composer.phar install
COMPOSER_PROCESS_TIMEOUT=999999999 php composer.phar update

VirtualHost
--------------------

    <VirtualHost *:80>
        ServerName nomeDoProjeto
        DocumentRoot /var/www/nomeDoProjeto/public

        SetEnv APPLICATION_ENV "development"
        SetEnv PROJECT_ROOT "/var/www/nomeDoProjeto"

        <Directory "/var/www/nomeDoProjeto/public">
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>

    </VirtualHost>


Hosts
----------------
    echo "127.0.0.1 nomeDoProjeto" >> /etc/hosts

Apache enbutido no Php 5.4
----------------
No terminal digite

    php -S localhost:8080 -t public/