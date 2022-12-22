<?php

namespace App\Repository;


use App\Core\Application;
use App\Core\Db\DbModel;



class MailerRepository
{

    public static function findRequestNum(string $userTable , string $requestTable , string $email): array
    {
        $statement = self::prepare('SELECT users.id,name,status,COUNT(requests.id) FROM ' . $userTable . ' LEFT JOIN ' . $requestTable . ' ON users.id = requests.user WHERE email = ?');
        $statement->bindValue(1 , $email);

        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);

    }


    public function insertToken($tableName , array $where)
    {
        $keys = array_keys($where);
        $values = array_map(fn($attr) => ":$attr" , $keys);
        $statement = self::prepare(
            "INSERT INTO $tableName (" . implode(',' , $keys) . ") VALUES(" . implode(',' , $values) . ")"
        );
        foreach($where as $key => $value)
        {
            $statement->bindValue(":$key" , $value);
        }
        $statement->execute();
    }

    public function verifyRequest($requestsTable ,$id , $hash): bool
    {
        $statement = self::prepare('SELECT user , token FROM ' . $requestsTable . ' WHERE user= :id AND type = 0');
        $statement->bindValue(':id' , $id);
        $statement->execute();


        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if($row)
        {
          
             if ($this->decode($hash , $row))
             {
                 return true;
             }


            
        }
        return false;
    }

    public function updateUsers($userTable , $id): bool
    {

       $statement = self::prepare('UPDATE '. $userTable .' SET status= ? WHERE id=?');
       $statement->bindValue(1 , 1);
       $statement->bindValue(2 , $id);
        if($statement->execute())
        {
            return true;
        }return false;
    }

    public function deleteRequest($userTable,$id)
    {

        $statement = self::prepare('DELETE FROM ' . $userTable . ' WHERE user = ? AND type = 0');
        $statement->bindValue(1 , $id);
        if($statement->execute())
        {
            return true;
        }return false;
    }

    public function decode($hash , $result): bool
    {
        $hex = urldecode($hash);

        if ($hex === $result['token'])
        {
            return true;
        }return false;
    }

    private static function prepare($QUERY)
    {
       return Application::$app->db->prepare($QUERY);
    }
}