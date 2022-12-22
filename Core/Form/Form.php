<?php



namespace App\Core\Form;

use App\Core\Model;

class Form
{

    public static function begin($action , $method)
    {
        echo sprintf('<form action="%s" method="%s" id="form" class="form__container" enctype="multipart/form-data">' ,$action , $method );
        return new Form();
    }


    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute , $id , $class )
    {
        return new InputField($model, $attribute , $id ,$class);
    }

}