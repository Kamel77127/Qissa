<?php


$psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

$creator = new \Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory

);
$cart = new \App\Model\CartModel();
$cart->setTotal();
$paypal = new \Mezia\Mvc\Service\PaypalPayment();
$request = $creator->fromGlobals();
$paypal->handle($cart);
