<?php

class m0003_create_blog_tables
{
    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY =  "CREATE TABLE `blog_articles` (
        id int NOT NULL AUTO_INCREMENT,
        articleTitle text NOT NULL,
        paragraphe1 longtext NOT NULL,
        principalImage varchar(255) NOT NULL,
        createdAt varchar(255) DEFAULT '' NOT NULL,
                  updatedAt varchar(255) DEFAULT '' NOT NULL,
                  deletedAt varchar(255) DEFAULT '' , 
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);

    }
}