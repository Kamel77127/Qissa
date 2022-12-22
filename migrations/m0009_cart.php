<?php

class m0009_cart
{
    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY = "CREATE TABLE `cart`(
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        productId INT NOT NULL,
        productName VARCHAR(255) NOT NULL,
        image VARCHAR(255) NOT NULL,
         quantity INT NOT NULL,
         price INT NOT NULL,
         subPrice FLOAT NOT NULL,
        PRIMARY KEY (id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);
    }
}