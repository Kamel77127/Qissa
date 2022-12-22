<?php

namespace App\Core\Form;

use App\Core\Model;

abstract class BaseField
{

    public Model $model;
    public string $attribute;
    public string $id = '';
    public string $class = '';

    public function __construct(Model $model , string $attribute , string $id , string $class)
    {

        $this->model = $model;
        $this->attribute = $attribute;
        $this->id = $id;
        $this->class = $class;

    }


    abstract public function renderInput(): string;

    public function __toString()
    {
        return sprintf('
   
            <label for="%s" class="label__input">%s</label>
            %s
            <div class="error__message">
            %s
            </div>
        ',
            $this->id,
            $this->model->getLabels($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );

    }
}