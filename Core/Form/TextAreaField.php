<?php

namespace App\Core\Form;

class TextAreaField extends BaseField
{

    public function renderInput(): string
    {
        return sprintf('<textarea name="%s" class="paragraphe__form%s" cols="30" rows="20" style="white-space: pre;">%s</textarea>',

            $this->attribute,
            $this->model->hasError($this->attribute) ? ' invalid-input' : '',
            $this->model->{$this->attribute},

        );
    }
}