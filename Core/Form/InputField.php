<?php


namespace App\Core\Form;

use App\Core\Application;
use App\Core\Model;

class InputField extends BaseField
{

    public const TYPE_FILE = 'file';
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public string $type;


public function __construct(Model $model , string $attribute , string $id , string $class)
{

    $this->type = self::TYPE_TEXT;
    parent::__construct($model, $attribute , $id , $class);

}

public function renderInput(): string
{
   return sprintf('<input type="%s" name="%s" id="%s" value="%s" class="%s">',
       $this->type,
       $this->attribute,
       $this->id,
       is_array($this->model->{$this->attribute}) ? '/assets/images_uploads/BlogImage/' . $this->model->{$this->attribute}['name']   : $this->model->{$this->attribute},
       $this->model->hasError($this->attribute) ? 'invalid-input' : $this->class,

);

}



public function passwordField()
{
    $this->type = self::TYPE_PASSWORD;
    return $this;
}

public function fileInput()
{
    $this->type = self::TYPE_FILE;
    return $this;
}

public function numberInput()
{
    $this->type = self::TYPE_NUMBER;
    return $this;
}

}