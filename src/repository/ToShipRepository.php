<?php

namespace App\Repository;

use App\Core\Application;
use App\Core\Model;

class ToShipRepository
{


    public function save($uid , $pid , $pquantity, $ttc , $delivery , $pstatus = 'PAID')
    {
        $tableName = 'toship';
        $statement = self::prepare("INSERT INTO $tableName (`user_id` , `productId` , `quantity` , `total` ,`delivery` , `statusPayment`)
 VALUES(? , ? , ? , ? , ? , ?)");
        $statement->bindValue(1,$uid);
        $statement->bindValue(2,$pid);
        $statement->bindValue(3,$pquantity);
        $statement->bindValue(4,$ttc);
        $statement->bindValue(5,$delivery);
        $statement->bindValue(6,$pstatus);

        $statement->execute();

    }


    public static function prepare($query)
    {
        return Application::$app->db->prepare($query);
    }

}