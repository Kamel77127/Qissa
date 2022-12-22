<?php


class m0001_initial
{
    public function up()
    {
        $dbb = \App\Core\Application::$app->db->pdo;
        $QUERY = "CREATE TABLE users (
                  id INT AUTO_INCREMENT PRIMARY KEY,
                  email VARCHAR(255) NOT NULL,
                  name VARCHAR(255) NOT NULL,
                  status TINYINT NOT NULL,
                  role VARCHAR(15) NOT NULL,               
                  password VARCHAR(512) NOT NULL,
                  createdAt varchar(255) DEFAULT '' NOT NULL,
                  updatedAt varchar(255) DEFAULT '' NOT NULL,
                  deletedAt varchar(255) DEFAULT ''                  
                  ) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;";
        $dbb->exec($QUERY);
    }

    public function down()
    {
        $dbb = \App\Core\Application::$app->db;
        $QUERY = "DROP TABLE users;";
        $dbb->pdo->exec($QUERY);
    }
}