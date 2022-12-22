<?php

namespace App\Core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';



    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach($flashMessages as $key => &$flashMessage)
        {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message,

        ];
    }

    public function setCart(string $productName , int $productId , int $price , string $image): array
    {
        return $_SESSION['cart'][$productName] =
            ['quantity' => 1, 'price' => $price , 'productId' => $productId , 'image' => $image , 'productName' => $productName];
    }

    public function removeCart(int $pid)
    {
        foreach($_SESSION['cart'] as $key => $value)
        {

            if($_SESSION['cart'][$key]['productId'] === $pid)
            {
                unset($_SESSION['cart'][$key]);
            }
        }
    }

    public function unsetCart()
    {
        foreach($_SESSION['cart'] as $key => $value)
        {

                unset($_SESSION['cart'][$key]);
            
        } 
    }
    public function updateCartQuantity($pid , $pQuantity)
    {
        foreach($_SESSION['cart'] as $key => $value)
        {
            if($_SESSION['cart'][$key]['productId'] === $pid)
            {

                return $_SESSION['cart'][$key]['quantity'] = $pQuantity;
            }

        }

    }
    public function productIdExist(string $productName) : bool
    {
       if(isset($_SESSION['cart'][$productName]))
       {
           return true;
        }else {
           return false;
       }
    }

    public function addQuantity(string $productName)
    {
        return $_SESSION['cart'][$productName]['quantity'] +=1;
    }

    public function getSessionCart()
    {
       if(isset($_SESSION['cart'])) {
           return $_SESSION['cart'];
       }
       return null;
    }
    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ??  false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($flashMessages as $key => &$flashMessage)
        {
           if($flashMessage['remove'])
           {
               unset($flashMessages[$key]);
           }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}