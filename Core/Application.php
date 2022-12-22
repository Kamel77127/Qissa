<?php


namespace App\Core;



use App\Core\Db\Database;
use App\Core\PHPMailer\Mailer;

class Application
{

public static string $ROOT_DIR;

public string $layout = 'baseTemplate';
public string $userClass;
public Router $router;
public Request $request;
public Response $response;
public Database $db;
public static Application $app;
public ?Controller $controller = null;
public Session $session;
public ?UserModel $user;
public View $view;
public Mailer $mailer;
public FilesMethod $files;
public CSRF $csrf;
public Cookies $cookies;


public function __construct($rootPath , array $config)
{
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->userClass = $config['userClass'];
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router();
    $this->session = new Session();
    $this->mailer = new Mailer($config['smtp']);
    $this->files = new FilesMethod();
    $this->db = new Database($config['db']);
    $this->view = new View();
    $this->csrf = new CSRF();
    $this->cookies = new Cookies();

    $primaryValue = $this->session->get('user');
    if($primaryValue) {
        $primaryKey = $this->userClass::primaryKey();
      $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
    }else {
        $this->user = null;
    }
}

public function run()
{
    try {
        echo $this->router->resolve();
    }catch (\Exception $e)
    {
        echo $this->view->renderViews('_error', [
            'exception' => $e
        ]);
    }

}

public function setController(Controller $controller)
{
     $this->controller = $controller;
}

public function getController()
{
    return $this->controller;
}

public function login(UserModel $user)
{
    $this->user = $user;
    $primaryKey = $user->primaryKey();
    $primaryValue = $user->{$primaryKey};
    $this->session->set('user', $primaryValue);
    return true;
}

public function logout()
{
    $this->user = null;
    $this->session->remove('user');
}

public static function isGuest()
{
    return !self::$app->user;
}

public static function isAdmin()
{
   if($user = self::$app->session->get('user'))
   {
       $role = Application::$app->user->findRole($user);
       return $role === '[ROLE_ADMIN]'; 
   }

}

public static function dd(mixed $info)
{
    echo "<pre>";
    print_r($info);
    echo "</pre>";
}
}











