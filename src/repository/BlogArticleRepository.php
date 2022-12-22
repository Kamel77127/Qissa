<?php

namespace App\Repository;

use App\Core\Application;
use App\Core\Model;

abstract class BlogArticleRepository extends Model
{

    abstract public function tableName(): array;
    abstract public function getImagesAttr(): array;
    abstract public function getPrincipalBlogInfoAttr(): array;
    abstract public function getNotesAttr(): array;
    abstract public function getParagraphesAttr(): array;






    public function save(): bool
    {
        $tableNames = $this->tableName();
        $images = $this->getImagesAttr();
        $principalData = $this->getPrincipalBlogInfoAttr();
        $notes = $this->getNotesAttr();
        $paragraphes = $this->getParagraphesAttr();


        foreach($tableNames as $tableName)
        {
            switch ($tableName) {
                case 'blog_articles':

                    $params = implode(',' ,  array_map(fn($attr) => ":$attr" , $principalData));

                    $statement = self::prepare("INSERT INTO $tableName (".implode(',' , $principalData).") VALUES ($params);");

                    foreach($principalData as $values)
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

                    case 'article_images' :
                    $params = implode(',' , array_map(fn($attr) => ":$attr" , $images));
                    $statement = self::prepare("INSERT INTO $tableName (" . implode(',' , $images) . ", id) VALUES ($params, LAST_INSERT_ID());");
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

                case 'article_notes':
                    $params = implode(',' , array_map(fn($attr) => ":$attr" , $notes));
                    $statement = self::prepare("INSERT INTO $tableName (".implode(',' , $notes) . ", id) VALUES($params , LAST_INSERT_ID())");
                    foreach($notes as $values)
                    {
                        $statement->bindValue(":$values" , $this->{$values});
                    }
                    $statement->execute();
                    break;

                case 'article_paragraphes':
                    $params = implode(',' , array_map(fn($attr) => ":$attr" , $paragraphes));
                    $statement = self::prepare("INSERT INTO $tableName (" . implode(',' , $paragraphes) . ", id) VALUES($params , LAST_INSERT_ID())");
                    foreach($paragraphes as $values)
                    {
                        $statement->bindValue(":$values" , $this->{$values});
                    }
                    $statement->execute();
                    break;

            }


        }
        return true;
    }

    public function getAllArticle(int $offset , int $max , string $deleted)
    {
        $tableNames = $this->tableName()[0];
        $statement = self::prepare("SELECT * FROM $tableNames WHERE deletedAt $deleted LIMIT ? OFFSET ?");

        $statement->bindValue(1, $max , \PDO::PARAM_INT);
        $statement->bindValue(2 , $offset , \PDO::PARAM_INT) ;
        $statement->execute();

        return $statement;
    }

    public function readArticle(int $id)
    {
        $tableNames = $this->tableName();

        $principalBlogAttributes = array_map(fn($a)=>"ba.$a",$this->getPrincipalBlogInfoAttr());
        $paragraphes = array_map(fn($a)=> "ap.$a" , $this->getParagraphesAttr());
        $images = array_map(fn($a)=>"ai.$a", $this->getImagesAttr());
        $notes = array_map(fn($a) => "an.$a", $this->getNotesAttr());
        foreach($principalBlogAttributes as $key => $value)
        {
            $principal[$key] = $value;
        }
        foreach($paragraphes as $key => $value)
        {
            $paragraphes[$key] = $value;
        }
        foreach($images as $key => $value)
        {
            $images[$key] = $value;
        }
        foreach($notes as $key => $value)
        {
            $notes[$key] = $value;
        }
        $data = array_merge($principal , $paragraphes , $images , $notes);
        $params = implode(',' , $data);



        $statement = self::prepare("SELECT $params FROM $tableNames[0] ba INNER JOIN $tableNames[1] ai ON ba.id = ai.id 
        INNER JOIN $tableNames[2] ap ON ba.id = ap.id INNER JOIN $tableNames[3] an ON ba.id = an.id WHERE ba.id = ?");
        $statement->bindValue(1 , $id);
        $statement->execute();
        return $statement;

    }

    public function getArticleForUpdate(int $id)
    {
        $tableNames = $this->tableName();


        $principalBlogAttributes = array_map(fn($a) => "ba.$a",$this->getPrincipalBlogInfoAttr());
        $paragraphes = array_map(fn($a)=>"ap.$a", $this->getParagraphesAttr());
        $images = array_map(fn($a)=>"ai.$a", $this->getImagesAttr());
        $data = implode(',' , array_merge($principalBlogAttributes , $paragraphes , $images));


        $statement = self::prepare("SELECT $data FROM $tableNames[0] ba INNER JOIN $tableNames[2] ap ON ba.id = ap.id INNER JOIN $tableNames[1] ai ON ba.id = ai.id WHERE ba.id = ?");
        $statement->bindValue(1 , $id);

        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);


    }
    private static function prepare($query)
    {
        return Application::$app->db->prepare($query);
    }

    public function update(int $id): bool
    {
        $tableNames = $this->tableName();
        $images = $this->getImagesAttr();
        $principalData = $this->getPrincipalBlogInfoAttr();
        $notes = $this->getNotesAttr();
        $paragraphes = $this->getParagraphesAttr();


        foreach($tableNames as $tableName)
        {
            switch ($tableName) {
                case 'blog_articles':

                    $params = implode(',' ,  array_map(fn($attr) => "$attr = :$attr" , $principalData));


                    $statement = self::prepare("UPDATE $tableName SET $params WHERE id = :id;");
                    $statement->bindValue(":id" , $id);
                    foreach($principalData as $values)
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

                case 'article_images' :

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

                case 'article_notes':
                    $params = implode(',' , array_map(fn($attr) => "$attr = :$attr" , $notes));
                    $statement = self::prepare("UPDATE $tableName SET $params WHERE id = :id");
                    $statement->bindValue(":id" , $id);
                    foreach($notes as $values)
                    {
                        $statement->bindValue(":$values" , $this->{$values});
                    }
                    $statement->execute();

                    break;


                case 'article_paragraphes':
                    $params = implode(',' , array_map(fn($attr) => "$attr = :$attr" , $paragraphes));
                    $statement = self::prepare("UPDATE $tableName SET $params WHERE id = :id");
                    $statement->bindValue(":id" , $id);
                    foreach($paragraphes as $values)
                    {
                        $statement->bindValue(":$values" , $this->{$values});
                    }
                    $statement->execute();



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

    public function countArticle()
    {
        $tableName = $this->tableName()[0];
        $statement = self::prepare("SELECT COUNT(id) FROM $tableName");
        $statement->execute();
         return $statement->fetch(\PDO::FETCH_NUM)[0];
    }
}