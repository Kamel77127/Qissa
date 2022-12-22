<?php

namespace App\Repository;

use App\Core\Application;
use App\Core\Model;
use PHPMailer\PHPMailer\Exception;

abstract class CartRepository extends Model
{

    public abstract function tableName(): string;
    public abstract function getUserId(): int;
    public abstract function getProduct(): array;


    public function save()
    {
      $tableName = $this->tableName();
      $userId = $this->getUserId();
      $products =  $this->getProduct();

      foreach($products as $key => $value)
      {
          $statement = self::prepare("INSERT INTO $tableName (`userId` , `productId` , `quantity` , `subPrice` , `image` , `productName` , `price`) VALUES (? , ? , ? , ? ,?,?, ?);");
          $statement->bindValue(1 , $userId);
          $statement->bindValue(2 , $value['productId']);
          $statement->bindValue(3 , $value['quantity']);
          $statement->bindValue(4 , $value['0']);
          $statement->bindValue(5 , $value['image']);
          $statement->bindValue(6 , $value['productName']);
          $statement->bindValue(7 , $value['price']);


          $statement->execute();
      }

    }

    public function deleteRows(array $pid , int $uid)
    {
        $tableName = $this->tableName();


        foreach($pid as $key)
        {

            $statement = self::prepare("DELETE FROM $tableName WHERE productId = ? AND userId = ?");
            $statement->bindValue(1 , $key);
            $statement->bindValue(2 , $uid);
            $statement->execute();
        }

    }
    public function cartExist(int $uid , array $productId = null)
    {

        $tableName = $this->tableName();
        if($productId){
            foreach($productId as $key)
            {
                $statement = self::prepare("SELECT productId , userId FROM $tableName WHERE productId = :productId AND userId = :uid");
                $statement->bindValue(':productId' , $key);
                $statement->bindValue(':uid' , $uid);

            }
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_ASSOC);

            if($row && $row > 0 )
            {
                return true;
            }else {
                return false;
            }
        }



    }

    public function updateQuantity(int $uid)
    {
        $tableName = $this->tableName();
        $products = $this->getProduct();

        foreach ($products as $key => $value)
        {
            $statement = self::prepare("UPDATE $tableName SET quantity = :quantity , subPrice = :subPrice WHERE productId = :pid AND userId = :id");
            $statement->bindValue(':quantity' , $value['quantity']);
            $statement->bindValue(':subPrice' , $value[0]);

            $statement->bindValue(':pid' , $value['productId']);
            $statement->bindValue(':id' , $uid);
            $statement->execute();
        }

    }

    public function updateQuantityById($uid , $pid , $pQuantity , $subprice)
    {
        $tableName = $this->tableName();
        $statement = self::prepare("UPDATE $tableName SET quantity = ? , subPrice = ? WHERE userId = ? AND productId = ?");
        $statement->bindValue(1 , $pQuantity);
        $statement->bindValue(2 , $subprice);
        $statement->bindValue(3 , $uid);
        $statement->bindValue(4 , $pid);
        $statement->execute();


    }

    public function findAllWhereUid(int $uid)
    {
        $tableName = $this->tableName();
        $statement = self::prepare("SELECT * FROM $tableName WHERE userId = :uid");
        $statement->bindValue(':uid', $uid);
        $statement->execute();
        return $statement;
    }

    public function findAllWhereUidPaypal(int $uid)
    {
        $tableName = $this->tableName();
        $statement = self::prepare("SELECT * FROM $tableName WHERE userId = :uid");
        $statement->bindValue(':uid', $uid);
        $statement->execute();
        return $statement->fetchAll();
    }


    public static function prepare($query)
    {
        return Application::$app->db->prepare($query);
    }

    public function removeFromCart(int $uid , int $pid)
    {
        $tableName = $this->tableName();
        $statement = self::prepare("DELETE FROM $tableName WHERE userId = ? AND productId = ?");
        $statement->bindValue(1 , $uid);
        $statement->bindValue(2 , $pid);
        $statement->execute();

    }

    public function selectRandomProduct()
    {
        $statement = self::prepare("SELECT * FROM products ORDER BY RAND() LIMIT 4");
        $statement->execute();
        return $statement;
    }

    public function findOrders()
    {
        $tableName = 'toship';
        $statement = self::prepare("SELECT * FROM $tableName ");
        $statement->execute();
        return $statement;
    }

    public function deleteCart($uid)
    {
        $tableName = $this->tableName();
        $statement =self::prepare("DELETE FROM $tableName WHERE userId=?;");
        $statement->bindValue(1 , $uid);
        $statement->execute();

    }

}