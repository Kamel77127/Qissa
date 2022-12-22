<?php

class m0007_products_table
{

    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY = "CREATE TABLE `products`(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    principalImage VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price FLOAT NOT NULL,
    stock INT NOT NULL,
    author VARCHAR(255) NOT NULL,
    pages INT NOT NULL,
    createdAt varchar(255) DEFAULT '' NOT NULL,
    updatedAt varchar(255) DEFAULT '' NOT NULL,
    deletedAt varchar(255) DEFAULT ''  ,
    PRIMARY KEY (id) 
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);
    }
}