<?php

namespace App\Controllers;

use Exception;
use App\Model\User;
use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;
use App\Model\CartModel;
use App\Core\Application;
use App\Model\ProductModel;
use App\Model\BlogArticleModel;
use App\Repository\CartRepository;
use App\Core\Middlewares\AdminMiddleware;

class AdminController extends Controller
{


    public function __construct()
    {
        $this->adminMiddleware(new AdminMiddleware([
            'createBlogArticle', 'showArticles', 'updateArticle', 'deleteArticle', 'archivePage', 'restoreArticle', 'showProducts',
            'archiveProducts', 'createProduct', 'updateProduct', 'deleteProduct', 'restoreProduct', 'showMembers', 'updateMembers'
        ]));
    }

    public function createBlogArticle(Request $request, Response $response)
    {

        $model = new BlogArticleModel();
        if ($request->isPost()) {

            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $model->loadData($request->getBody());
                $model->loadData($request->getFiles());
                $model->setCreatedAt();
                $model->setUpdatedAt();


                if ($model->validate() && $model->save()) {
                    Application::$app->session->setFlash('success', 'votre article a bien était crée.');
                    $response->redirect('/ ');
                    return;
                }
            }
        }

        $this->setLayout('secondLayout');
        return $this->render('admin/create_article', [
            'model' => $model
        ]);
    }


    public function showArticles(Request $request)
    {
        $model = new BlogArticleModel();
        $_GET['page'] = $request->getRequestUri($_SERVER['REQUEST_URI'])[2] ?? 1;

        (int)$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $maxRecPerPages = 6;
        $offset = $maxRecPerPages * ($currentPage - 1);
        if ($currentPage <= 0) {
            throw new Exception('Numéro de page invalide');
        }
        $count = $model->countArticle();
        $pages = ceil($count / $maxRecPerPages);

        if ($currentPage > $pages) {
            throw new Exception('cette page est invalide');
        }
        $deleted = "IS NULL";
        $rows = $model->getAllArticle($offset, $maxRecPerPages, $deleted);

        $this->setLayout('secondLayout');
        return $this->render('admin/show_articles', [
            'rows' => $rows,
            'currentPage' => $currentPage,
            'pages' => $pages
        ]);
    }


    public function updateArticle(Request $request, Response $response)
    {
        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];
        $model = new BlogArticleModel();
        $row = $model->getArticleForUpdate($id);

        $model->loadData($row);

        if ($request->isPost()) {

            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $row = $model->getArticleForUpdate($id);
                $model->loadData($row);
                $model->setUpdatedAt();

                if ($request->getFiles()) {
                    $model->loadData($request->getFiles());
                } else {
                    null;
                }
                $model->loadData($request->getBody());


                if ($model->validate() && $model->update($id)) {

                      Application::$app->session->setFlash('success' , 'votre article a bien été modifié');
                    $response->redirect('/');
                    return;
                }
            }
        }

        $this->setLayout('secondLayout');
        return $this->render(
            'admin/create_article',
            ['model' => $model]
        );
    }

    public function deleteArticle(Request $request, Response $response)
    {

        
    
        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];

        $model = new BlogArticleModel();

        $model->setDeletedAt();

        if ($model->delete($id, $model->deletedAt)) {

            $response->redirect('/admin/archive-article');
        }
    }

    public function archivePage()
    {
        $model = new BlogArticleModel();
        $deleted = "IS NOT NULL";
        $rows = $model->getAllArticle(0, 20, $deleted);

        $this->setLayout('secondLayout');
        return $this->render(
            '/admin/deleted_article',
            [
                'rows' => $rows
            ]
        );
    }

    public function restoreArticle(Request $request, Response  $response)
    {
        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];

        $model = new BlogArticleModel();
        $model->restore($id);
        $response->redirect('/admin/archive-article');
    }

    /////////////////////////////////////////////// PRODUCT CRUD PART \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    ///
    ///

    public function showProducts(Request $request)
    {
        $model = new ProductModel();
        $_GET['page'] = $request->getRequestUri($_SERVER['REQUEST_URI'])[2] ?? 1;

        (int)$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $maxRecPerPages = 6;
        $offset = $maxRecPerPages * ($currentPage - 1);
        if ($currentPage <= 0) {
            throw new Exception('Numéro de page invalide');
        }
        $count = $model->countProducts();
        $page = ceil($count / $maxRecPerPages);
  
        if ($currentPage > $page) {
            throw new Exception('cette page est invalide');
        }
        $deleted = "IS NULL";
        $rows = $model->getAllProduct($offset, $maxRecPerPages, $deleted);

        $this->setLayout('secondLayout');
        return $this->render('admin/show_product', [
            'rows' => $rows,
            'currentPage' => $currentPage,
            'page' => $page
        ]);
    }


    public function archiveProducts()
    {
        $model = new ProductModel();
        $deleted = "IS NOT NULL";
        $rows = $model->getAllProduct(0, 20, $deleted);

        $this->setLayout('secondLayout');
        return $this->render(
            '/admin/deleted_product',
            [
                'rows' => $rows
            ]
        );
    }

    public function createProduct(Request $request, Response $response)
    {
        $model = new ProductModel();

        if ($request->isPost()) {

            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $model->loadData($request->getBody());
                $model->loadData($request->getFiles());
                $model->setCreatedAt();
                $model->setUpdatedAt();

                if ($model->validate() && $model->save()) {
                    Application::$app->session->setFlash('success', 'votre produit a bien était créée');
                    $response->redirect('/admin/create-product');
                }
            }
        }
        $this->setLayout('secondLayout');
        return $this->render('admin/create_product', [
            'model' => $model
        ]);
    }

    public function updateProduct(Request $request, Response $response)
    {
        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];

        $model = new ProductModel();
        $row = $model->getProductForUpdate($id);

        $model->loadData($row);

        if ($request->isPost()) {

            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $row = $model->getProductForUpdate($id);

                $model->loadData($row);

                $model->setUpdatedAt();

                if ($request->getFiles()) {
                    $model->loadData($request->getFiles());
                } else {
                    null;
                }
                $model->loadData($request->getBody());


                if ($model->validate() && $model->update($id)) {

                    Application::$app->session->setFlash('success' , 'votre produit a bien été modifié');
                    $response->redirect('/');
                    return;
                }
            }
        }
        $this->setLayout('secondLayout');
        return $this->render('admin/create_product', [
            'model' => $model
        ]);
    }


    public function deleteProduct(Request $request, Response $response)
    {

        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];

        $model = new ProductModel();

        $model->setDeletedAt();

        if ($model->delete($id, $model->deletedAt)) {

            $response->redirect('/admin/archive-produits');
        }
    }

    public function restoreProduct(Request $request, Response  $response)
    {
        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];

        $model = new ProductModel();
        $model->restore($id);
        $response->redirect('/admin/archive-produits');
    }


    /////////////////////////// MEMBRES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    public function showMembers(Request $request)
    {
        $model = new User();
        $rows = $model->findAll();

        $this->setLayout('secondLayout');
        return $this->render('/admin/show_members', ['rows' => $rows]);
    }

    public function updateMembers(Request $request, Response $response)
    {
        $model = new User();

        if ($request->isPost()) {

            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];
                $row = $model->findById($id);

                $model->loadData($row);
                $model->role = strip_tags(htmlspecialchars($_POST['role']));

                $model->setUpdatedAt();
                $model->update($id);
                $response->redirect('/admin/voir-utilisateurs');
                
            }
        }
        $this->setLayout('secondLayout');
        return $this->render('/admin/update_user', ['model' => $model]);
    }

    public function showOrders()
    {
        $model = new CartModel;
        $rows = $model->findOrders();

        $this->setLayout('secondLayout');
        return $this->render('/admin/show_orders', ['rows' => $rows]);
    }
}
