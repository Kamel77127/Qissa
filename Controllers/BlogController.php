<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Model\BlogArticleModel;
use Exception;

class BlogController extends Controller
{

public function index(Request $request , Response $response)
{
    $currentUri = $_SERVER['REQUEST_URI'];
    if ($request->isPost()) {

        if(isset($_POST['cookieConsent']))
        {
            Application::$app->cookies->verifyPostCookieRequest();
            $response->redirect("$currentUri");
        }else if(isset($_POST['rejectCookie']))
        {
            Application::$app->cookies->verifyPostCookieRequest();
            $response->redirect("$currentUri");
        }
    }

    $model = new BlogArticleModel();
    $_GET['page'] = $request->getRequestUri($_SERVER['REQUEST_URI'])[0] ?? 1;

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $maxRecPerPages = 6;
    $offset = $maxRecPerPages * ($currentPage - 1);
    if($currentPage <= 0)
    {
        throw new Exception('Numéro de page invalide');
    }
    $count = $model->countArticle();
    $pages = ceil($count / $maxRecPerPages);

    if($currentPage > $pages)
    {
        throw new Exception('cette page est invalide');
    }
    $deleted = "IS NULL";
    $rows = $model->getAllArticle( $offset , $maxRecPerPages , $deleted);

    return $this->render('/Blog/accueil', [
        'rows' => $rows,
        'currentPage' => $currentPage,
        'pages' => $pages
    ]);
}

public function articlePage(Request $request)
{

    $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[0];
    $model = new BlogArticleModel();
    $row = $model->readArticle($id);

    $this->setLayout('secondLayout');
    return $this->render('Blog/article_page' , [
        'row' => $row,

    ]);
}







}