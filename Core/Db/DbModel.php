<?php

namespace App\Core\Db;

use App\Core\Application;
use App\Core\Model;

abstract class DbModel extends Model
{

    abstract static public function tableName(): string;

    abstract static public function attributes(): array;

    abstract static public function primaryKey(): string;



    public function save()
    {
    $tableName = $this->tableName();
    $attributes = $this->attributes();
    $params = array_map(fn($attr) => ":$attr", $attributes);
    $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") 
    VALUES(".implode(',', $params).");");

    foreach($attributes as $attribute)
    {
        $statement->bindValue(":$attribute", $this->{$attribute});
    }
    $statement->execute();
    return true;
    }


    public static function prepare($QUERY)
    {
        return Application::$app->db->pdo->prepare($QUERY);
    }


    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $QUERY = implode("AND",array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $QUERY");
        foreach($where as $key => $item)
        {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public function findRole(int $uid)
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT role FROM $tableName WHERE id = ?");
        $statement->bindValue(1 , $uid);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC)['role'];
    }

    public function findAll()
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement;
    }

    public function findById(int $id)
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName WHERE id = ?");
        $statement->bindValue(1 , $id);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(int $id)
    {
        $tableName = static::tableName();
        $attributes = static::attributes();
        $params = implode(',' ,array_map(fn($attr) => "$attr = :$attr", $attributes));

        $statement = self::prepare("UPDATE $tableName SET $params WHERE id = :id");
        $statement->bindValue(":id" , $id);
        foreach($attributes as $attr)
        {
            $statement->bindValue(":$attr" , $this->{$attr});
        }
        $statement->execute();
    }



}