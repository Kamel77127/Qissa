<?php

namespace App\Model;

use App\Core\Application;
use App\Core\Model;
use App\Repository\MailerRepository;


class RegistrationMailer extends Model
{

    static $user;


    public array $request;
    public int $countRequest;
    public string $token = '';
    public string $email = '';
    public MailerRepository $mailerRepository;

    public function __construct()
    {
    
        $this->mailerRepository = new MailerRepository();
    }

    public function setUserInfo(User $user)
    {
        self::$user = $user;
        $this->email = self::$user->email;

    }

    public function tableName()
    {
        return 'requests';
    }

    public function getRequestObj()
    {
        $this->request = $this->mailerRepository->findRequestNum(User::tableName(), $this->tableName(), $this->email);
        $this->loadCountValue();

    }

    private function loadCountValue()
    {
        if ($this->request) {
            $this->countRequest = $this->request['COUNT(requests.id)'];
        };
    }

    public function rules(): array
    {
        return [
            $this->countRequest => [[self::RULE_MAX_REQUEST, 'max_request' => 3, 'value' => $this->request['COUNT(requests.id)']]]
        ];
    }

    public function createToken()
    {
        $code = random_bytes(16);
        $token = urlencode(bin2hex($code));

        $this->token = $token;


    }

    public function prepareInsertToken()
    {
        $this->mailerRepository->insertToken($this->tableName(), ['token' => $this->token, 'user' => $this->request['id'], 'type' => 0]);
    }

    public function prepareEmail()
    {
        if (Application::$app->mailer->sendMail($this->email, $this->request['name'], 'Verification Email :', '<a href="https://www.qisa.fr/email-validation/' . $this->request['id'] . '/' . $this->token . '">Cliquez sur le lien pour verifier votre email</a>')) {
            return true;
        } else {
            return false;
        }

    }



}