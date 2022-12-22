<?php

class m0008_products_image
{
    public function up()
    {
        $pdo = \App\Core\Application::$app->db->pdo;
        $QUERY = "CREATE TABLE `product_image`(
    id INT NOT NULL,
    img1 VARCHAR(255),
    img2 VARCHAR(255),
    img3 VARCHAR(255),
    img4 VARCHAR(255),
    img5 VARCHAR(255),
      PRIMARY KEY (id),
    FOREIGN KEY (id) REFERENCES products(id)  
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($QUERY);
    }
}