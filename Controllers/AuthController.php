<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;
use App\Core\Controller;
use App\Core\Response;
use App\Model\LoginForm;
use App\Model\RegistrationMailer;
use App\Model\User;
use App\Repository\MailerRepository;


class AuthController extends Controller
{


    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }


    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        $currentUri = $_SERVER['REQUEST_URI'];
        if ($request->isPost()) {

            if (isset($_POST['cookieConsent'])) {
                Application::$app->cookies->verifyPostCookieRequest();
                $response->redirect("$currentUri");
            }else if(isset($_POST['rejectCookie']))
            {
                Application::$app->cookies->verifyPostCookieRequest();
                $response->redirect("$currentUri");
            }

            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $loginForm->loadData($request->getBody());
                if ($loginForm->validate() && $loginForm->login()) {
                    $response->redirect('/');

                    return;
                }
            }
        }
        return $this->render('authentification/login', [
            'model' => $loginForm
        ]);
    }




    public function register(Request $request, Response $response)
    {
        $user = new User();
        $currentUri = $_SERVER['REQUEST_URI'];
        if ($request->isPost()) {

            if (isset($_POST['cookieConsent'])) {
                Application::$app->cookies->verifyPostCookieRequest();
                $response->redirect("$currentUri");
            }else if(isset($_POST['rejectCookie']))
            {
                Application::$app->cookies->verifyPostCookieRequest();
                $response->redirect("$currentUri");
            }



            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $user->loadData($request->getBody());

                if ($user->validate() && $user->save()) {
                    $mailer = new RegistrationMailer();
                    $mailer->setUserInfo($user);
                    $mailer->getRequestObj();

                    if ($mailer->request['COUNT(requests.id)'] === 0 && $mailer->validate()) {
                        $mailer->createToken();
                        $mailer->prepareInsertToken();
                        if ($mailer->prepareEmail()) {
                            Application::$app->session->setFlash('success', 'Votre compte a bien était créée, veuillez vérifier votre Email en cliquant sur le lien qui vous a était envoyé.');
                            Application::$app->response->redirect('/');
                        }
                    }
                    exit();
                }
            }
        }

        return $this->render('authentification/register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $responses)
    {
        Application::$app->logout();
        $responses->redirect('/');
    }

    public function profile()
    {

        return $this->render('userInterface/profile');
    }


    public function emailValidationPage(Request $request)
    {
        $data = $request->getRequestUri($_SERVER['REQUEST_URI']);
        $mailerModel = new RegistrationMailer();
        $mailRepo = new MailerRepository();
        $id = array_shift($data);
        echo $id;
        $hash = array_shift($data);
        if ($mailRepo->verifyRequest($mailerModel->tableName(), $id, $hash)) {
            if ($mailRepo->updateUsers(User::tableName(), $id) && $mailRepo->deleteRequest($mailerModel->tableName(), $id)) {
                Application::$app->session->setFlash('success', 'Votre compte a bien était confirmé');
            }
        }

        $this->setLayout('secondLayout');
        return $this->render('emailVerification', ['mailer' => $mailRepo]);
    }
}
