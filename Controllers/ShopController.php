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
use App\Repository\ToShipRepository;
use Nyholm\Psr7Server\ServerRequestCreator;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsGetRequest;
use Psr\Http\Message\ServerRequestInterface;

class ShopController extends Controller
{


    public function shop(Request $request, Response $response)
    {

        $model = new ProductModel();
        $currentUri = $_SERVER['REQUEST_URI'];
        $_GET['page'] = $request->getRequestUri($_SERVER['REQUEST_URI'])[1] ?? 1;

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $maxRecPerPages = 8;
        $offset = $maxRecPerPages * ($currentPage - 1);
        if ($currentPage <= 0) {
            throw new Exception('Numéro de page invalide');
        }
        $count = $model->countProducts();

        $page = ceil($count / $maxRecPerPages);
        if ($currentPage > $page) {
            throw new Exception('numéro de page invalide');
        }
        $deleted = "IS NULL";
        $rows = $model->getAllProduct($offset, $maxRecPerPages, $deleted);
        $cartModel = new CartModel();

        if (isset($_SESSION['cart']) && isset($_SESSION['user'])) {
            $cartModel->setProducts();
            $cartModel->setUserId();
            $cartModel->calculateSubPrice();

            if ($cartModel->cartExist($cartModel->userId, $cartModel->getProductId())) {
                $cartModel->deleteRows($cartModel->getProductId(), $cartModel->userId);
                $cartModel->save();
            } else {
                $cartModel->save();
            }
        }


        // IF REQUEST IS POST
    
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


            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                if (isset($_POST['add_to_cart']) && isset($_SESSION['user'])) {

                    if (Application::$app->session->productIdExist($_POST['name'])) {
                        Application::$app->session->addQuantity($_POST['name']);
                    } else {
                        Application::$app->session->setCart($_POST['name'], $_POST['productId'], $_POST['price'], $_POST['image']);
                        $response->redirect("$currentUri");
                    }

                    $cartModel = new CartModel();
                    $cartModel->setProducts();
                    $cartModel->setUserId();
                    $cartModel->calculateSubPrice();

                    if ($cartModel->cartExist($cartModel->userId, $cartModel->getProductId())) {
                        $cartModel->deleteRows($cartModel->getProductId(), $cartModel->userId);
                        $cartModel->save();
                    } else {
                        $cartModel->save();
                    }
                } elseif (isset($_POST['add_to_cart']) && !isset($_SESSION['user'])) {
                    if (Application::$app->session->productIdExist($_POST['name'])) {
                        Application::$app->session->addQuantity($_POST['name']);
                    } else {
                        Application::$app->session->setCart($_POST['name'], $_POST['productId'], $_POST['price'], $_POST['image']);
                        $response->redirect("$currentUri");
                    }
                }
            }
        }

        return $this->render('Boutique/shop', [
            'rows' => $rows,
            'currentPage' => $currentPage,
            'page' => $page
        ]);
    }

    public function pageProduct(Request $request, Response $response)
    {
        $id = $request->getRequestUri($_SERVER['REQUEST_URI'])[1];
        $currentUri = $_SERVER['REQUEST_URI'];
        $model = new ProductModel();
        $row = $model->readProduct($id);

        if ($request->isPost()) {
            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {
                if (isset($_POST['add_to_cart']) && isset($_SESSION['user'])) {
                    if (Application::$app->session->productIdExist($_POST['name'])) {
                        Application::$app->session->addQuantity($_POST['name']);
                    } else {
                        Application::$app->session->setCart($_POST['name'], $_POST['productId'], $_POST['price'], $_POST['image']);
                        $response->redirect("$currentUri");
                    }
                    $cartModel = new CartModel();
                    $cartModel->setProducts();
                    $cartModel->setUserId();
                    $cartModel->calculateSubPrice();

                    if ($cartModel->cartExist($cartModel->userId, $cartModel->getProductId())) {
                        $cartModel->deleteRows($cartModel->getProductId(), $cartModel->userId);
                        $cartModel->save();
                    } else {
                        $cartModel->save();
                    }
                } elseif (isset($_POST['add_to_cart']) && !isset($_SESSION['user'])) {
                    if (Application::$app->session->productIdExist($_POST['name'])) {
                        Application::$app->session->addQuantity($_POST['name']);
                    } else {
                        Application::$app->session->setCart($_POST['name'], $_POST['productId'], $_POST['price'], $_POST['image']);
                        $response->redirect("$currentUri");
                    }
                }
            }
        }
        $this->setLayout('secondLayout');
        return $this->render('/Boutique/product_page', [
            'row' => $row
        ]);
    }

    public function cart(Request $request, Response $response)
    {

        if (!isset($_SESSION['user'])) {
            $response->redirect('/login');
        }

        $currentUri = $_SERVER['REQUEST_URI'];
        $cartModel = new CartModel();
        $cartModel->setTotal();
        

        $rand = $cartModel->selectRandomProduct();

        $productModel = new ProductModel();
        $rows = $cartModel->findAllWhereUid($_SESSION['user']);


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

            if(isset($_POST['status']) && $_POST['status'] === 'ok')
            {
               header('Content-Type: application/json');
                $cartModel->deleteCart(Application::$app->session->get('user'));
                Application::$app->session->unsetCart();
                Application::$app->session->setFlash('success' , 'Votre achat a bien était confirmé');
                echo '{"status":"ok"}';
                exit;


            }




            if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {

                $cartModel->setUserId();

                if (isset($_POST['remove'])) {

                    if (Application::$app->request->filter_string($_POST['remove'])) {
                        $cartModel->removeFromCart($cartModel->userId, $_POST['remove']);
                        $cartModel->removeFromArray($_POST['remove']);
                        Application::$app->session->removeCart($_POST['remove']);
                        $response->redirect("$currentUri");
                    }
                }
                if (isset($_POST['updateCart'])) {
                    unset($_POST['csrf_token'], $_POST['total'], $_POST['updateCart'], $_POST['productId'], $_POST['price'], $_POST['name'], $_POST['image']);

                    foreach ($_POST as $key => $value) {


                        if (Application::$app->request->filter_string($key) && Application::$app->request->filter_string($value)) {
                            Application::$app->session->updateCartQuantity($key, $value);
                            $cartModel->setProducts();
                            $cartModel->calculateSubPrice();
                            $cartModel->updateQuantityById($cartModel->userId, $key, $value, $cartModel->getSubPrice($key));
                            $response->redirect("$currentUri");
                        }
                    }
                }

                if (isset($_POST['csrf_token']) && Application::$app->csrf->checkToken($_POST['csrf_token'])) {

                    if (isset($_POST['add_to_cart']) && isset($_SESSION['user'])) {

                        if (Application::$app->session->productIdExist($_POST['name'])) {
                            Application::$app->session->addQuantity($_POST['name']);
                        } else {

                            Application::$app->session->setCart($_POST['name'], $_POST['productId'], $_POST['price'], $_POST['image']);
                        }

                        $cartModel->setProducts();
                        $cartModel->setUserId();
                        $cartModel->calculateSubPrice();
                        $cartModel->setTotal();
                       
                        $cartModel->deleteRows($cartModel->getProductId(), $cartModel->userId);
                        $cartModel->save();
               
                        $response->redirect("$currentUri");
                    }
                }

                if (isset($_POST['validatePayment']) && isset($_POST['expedition'])) {

                    print_r($_POST);
                    exit;
                    unset($_POST['csrf_token'], $_POST['validatePayment'], $_POST['productId'], $_POST['price'], $_POST['name'], $_POST['image']);

                    $total = array_pop($_POST);
                    $expedition = array_pop($_POST);
                    $uid = Application::$app->session->get('user');

                    $repository = new ToShipRepository();
                    if (Application::$app->request->filter_string($total) && Application::$app->request->filter_string($expedition)) {
                        foreach ($_POST as $key => $value) {
                            $repository->save($uid, $key, $value, $total, $expedition);
                            $cartModel->removeFromCart($cartModel->userId, $key);
                        }
                    }

                    unset($_SESSION['cart']);
                    $response->redirect('/');
                }
            }
        }

        return $this->render('/Boutique/cart_view', [
            'rows' => $rows,
            'rand' => $rand,
            'model' => $cartModel

        ]);
    }


    public function paypalCheckout()
    {
       
        return $this->render('/Boutique/paypal_validation' , [

        ]);
    }
}
