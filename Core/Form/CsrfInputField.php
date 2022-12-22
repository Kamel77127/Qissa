<?php

namespace App\Core\Form;

use App\Core\Application;

class CsrfInputField extends BaseField
{

    public function renderInput(): string
    {
       return sprintf('<input type="hidden" name="csrf_token" value="%s">',
       Application::$app->csrf->getToken());
    }
}