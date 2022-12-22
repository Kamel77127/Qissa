<?php

namespace App\Model;

use App\Core\Application;
use App\Core\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';


    public function labels():array
    {
        return
        [
            'email' => 'Email',
            'password' => 'mot de passe'
        ];
    }

    public function rules(): array
    {

        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function login()
    {

       $user = User::findOne(['email' => $this->email]);
        if(!$user)
        {
            $this->AddError('email' , 'L\'utilisateur n\'existe pas');
            return false;
        }
        if(!password_verify($this->password, $user->password))
        {
            $this->AddError('password' , 'Le mot de passe est incorrect');
            return false;
        }

        return Application::$app->login($user);

    }

}