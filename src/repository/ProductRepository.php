<?php

namespace App\Repository;

use App\Core\Application;
use App\Core\Model;

abstract class ProductRepository extends Model
{


    abstract public function tableName(): array;
    abstract public function principalData(): array;
    abstract public function imageData(): array;

    public function save(): bool
    {
        $tableNames = $this->tableName();
        $principalData = $this->principalData();
        $images = $this->imageData();
        foreach($tableNames as $tableName)
        {
            switch($tableName)
            {
                case 'products':
                    $params = implode(',' , array_map(fn($attr) => ":$attr" , $principalData));
                    $statement = self::prepare("INSERT INTO $tableName (" . implode(',' , $principalData) . ") VALUES($params)");

                    foreach ($principalData as $values){
                    switch ($values)
                    {
                        case 'principalImage':
                            if(is_array($this->{$values}))
                            {
                                $statement->bindValue(":$values" , $this->{$values}['name']);
                            }else {
                                $statement->bindValue(":$values" , $this->{$values});
                            }
                        break;
                        default:
                            $statement->bindValue(":$values" , $this->{$values});
                    }


                    }
                    $statement->execute();
                    break;
                case 'product_image':
                    $params = implode(',' , array_map(fn($attr) => ":$attr" , $images));
                    $statement = self::prepare("INSERT INTO $tableName (" . implode(',' , $images) . ", id) VALUES($params , LAST_INSERT_ID())");

                    foreach ($images as $values)
                    {
                        if(is_array($this->{$values}))
                        {
                            $statement->bindValue(":$values" , $this->{$values}['name']);
                        }else {
                            $statement->bindValue(":$values" , $this->{$values});

                        }
                    }
                    $statement->execute();
                    break;
            }
        }

    return true;
    }


    public function getAllProduct(int $offset , int $max , string $deleted)
    {
        $tableNames = $this->tableName()[0];
        $statement = self::prepare("SELECT * FROM $tableNames WHERE deletedAt $deleted LIMIT ? OFFSET ?");

        $statement->bindValue(1, $max , \PDO::PARAM_INT);
        $statement->bindValue(2 , $offset , \PDO::PARAM_INT) ;
        $statement->execute();
        
        return $statement;
    }


    public function readProduct(int $id)
    {
        $tableNames = $this->tableName();

        $principalData = array_map(fn($a)=>"p.$a",$this->principalData());
        $images = array_map(fn($a)=>"pi.$a", $this->imageData());
        foreach($principalData as $key => $value)
        {
            $principal[$key] = $value;
        }

        foreach($images as $key => $value)
        {
            $images[$key] = $value;
        }

        $data = array_merge($principal  , $images);
        $params = implode(',' , $data);


        $statement = self::prepare("SELECT $params, p.id FROM $tableNames[0] p INNER JOIN $tableNames[1] pi ON p.id = pi.id WHERE p.id = ?");
        $statement->bindValue(1 , $id);
        $statement->execute();
        return $statement;

    }


    public function getProductForUpdate(int $id)
    {
        $tableNames = $this->tableName();


        $principalAttr = array_map(fn($a) => "p.$a",$this->principalData());
        $images = array_map(fn($a)=>"pi.$a", $this->imageData());
        $data = implode(',' , array_merge($principalAttr , $images));


        $statement = self::prepare("SELECT $data FROM $tableNames[0] p INNER JOIN $tableNames[1] pi ON p.id = pi.id WHERE p.id = ?");
        $statement->bindValue(1 , $id);

        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);

    }


    public function update(int $id): bool
    {
        $tableNames = $this->tableName();
        $images = $this->imageData();
        $principalAttr = $this->principalData();


        foreach($tableNames as $tableName)
        {
            switch ($tableName) {
                case 'products':

                    $params = implode(',' ,  array_map(fn($attr) => "$attr = :$attr" , $principalAttr));


                    $statement = self::prepare("UPDATE $tableName SET $params WHERE id = :id;");
                    $statement->bindValue(":id" , $id);
                    foreach($principalAttr as $values)
                    {
                        switch ($values)
                        {
                            case 'principalImage':

                                if(is_array($this->{$values})) {
                                    $statement->bindValue(":$values", $this->{$values}['name']);
                                }else {
                                    $statement->bindValue(":$values" , $this->{$values});
                                }

                                break;
                            default :
                                $statement->bindValue(":$values" , $this->{$values});
                        }
                    }
                    $statement->execute();

                    break;

                case 'product_image' :

                    $params = implode(',' , array_map(fn($attr) => "$attr = :$attr" , $images));

                    $statement = self::prepare("UPDATE $tableName SET $params WHERE id = :id;");
                    $statement->bindValue(":id" , $id);

                    foreach($images as $values)
                    {

                        if(is_array($this->{$values})) {
                            $statement->bindValue(":$values", $this->{$values}['name']);
                        }else {
                            $statement->bindValue(":$values" , $this->{$values});
                        }

                    }
                    $statement->execute();
                    break;
            }
        }
        return true;
    }

    public function delete(int $id , string $deletedAt): bool
    {

        $tableName = $this->tableName()[0];
        $statement = self::prepare("UPDATE $tableName SET deletedAt = ? WHERE id = ?");
        $statement->bindValue(1 , $deletedAt);
        $statement->bindValue(2 , $id);
        if($statement->execute())
        {
            return true;
        }else {
            return false;
        }
    }

    public function restore(int $id): bool
    {
        $tableName = $this->tableName()[0];
        $statement = self::prepare("UPDATE $tableName SET deletedAt = NULL WHERE id = ?");
        $statement->bindValue(1 , $id);
        $statement->execute();
        return true;
    }

    public function countProducts()
    {
        $tableName = $this->tableName()[0];
        $statement = self::prepare("SELECT COUNT(id) FROM $tableName");
        $statement->execute();
        
        return $statement->fetch(\PDO::FETCH_NUM)[0];
    }

    public static function prepare($query)
    {
        return Application::$app->db->prepare($query);
    }

}