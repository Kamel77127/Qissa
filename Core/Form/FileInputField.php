<?php

namespace App\Core\Form;

use App\Core\Application;

class FileInputField extends BaseField
{


    public function renderInput(): string
    {
        return sprintf('<input type="file" name="%s" id="%s" value="%s" class="%s">',
            $this->attribute,
            $this->id,
            Application::$ROOT_DIR . '/public/assets/images_uploads/BlogImage/'.$this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'invalid-input' : $this->class,);
    }





}