<?php

class m0005_image_tables
{
    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY =  "CREATE TABLE `article_images` (
  id int NOT NULL ,
  imageArticle1 varchar(255) DEFAULT NULL,
  imageArticle2 varchar(255) DEFAULT NULL,
  imageArticle3 varchar(255) DEFAULT NULL,
  imageArticle4 varchar(255) DEFAULT NULL,
  imageArticle5 varchar(255) DEFAULT NULL,
  imageArticle6 varchar(255) DEFAULT NULL,
   imageArticle7 varchar(255) DEFAULT NULL,
  imageArticle8 varchar(255) DEFAULT NULL,
  imageArticle9 varchar(255) DEFAULT NULL,
  imageArticle10 varchar(255) DEFAULT NULL,
    PRIMARY KEY (id),

  FOREIGN KEY (id) REFERENCES blog_articles(id) ON UPDATE CASCADE ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);

    }
}