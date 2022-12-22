<?php

class m0002_register_mailing
{

    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
     $QUERY =  'CREATE TABLE `requests` (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    user bigint(20) unsigned DEFAULT NULL,
    token varchar(255) DEFAULT NULL,
    type int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
     $pdo->exec($QUERY);

    }
}