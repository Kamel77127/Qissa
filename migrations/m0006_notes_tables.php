<?php

class m0006_notes_tables
{
    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY =  "CREATE TABLE `article_notes` (
        id int NOT NULL,
        note1 text,
        note2 text,
        note3 text,
        note4 text,
        note5 text,
          PRIMARY KEY (id),
        FOREIGN KEY (id) REFERENCES blog_articles(id) ON UPDATE CASCADE ON DELETE CASCADE 
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);
    }


}