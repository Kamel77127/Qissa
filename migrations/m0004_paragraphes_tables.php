<?php

class m0004_paragraphes_tables
{
    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY =  "CREATE TABLE `article_paragraphes` (
  id int NOT NULL,
  paragraphe2 longtext,
  paragraphe3 longtext,
  paragraphe4 longtext,
  paragraphe5 longtext,
  paragraphe6 longtext,
  paragraphe7 longtext,
  paragraphe8 longtext,
  paragraphe9 longtext,
  paragraphe10 longtext,
   PRIMARY KEY (id),
  FOREIGN KEY (id) REFERENCES blog_articles(id) ON UPDATE CASCADE ON DELETE CASCADE                         
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);

    }
}