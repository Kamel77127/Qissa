<?php

class m00010_toship
{

    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY = "CREATE TABLE `toship`(
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        productId INT NOT NULL,
        quantity INT NOT NULL,
        total INT NOT NULL,
        delivery VARCHAR(255) NOT NULL,
        statusPayment VARCHAR(255) DEFAULT 'PAID',
        statusDelivery VARCHAR(255) DEFAULT '',
        PRIMARY KEY (id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);
    }
}