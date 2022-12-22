<?php

namespace Mezia\Mvc\Service;

use App\Core\Application;
use App\Core\Exception\PaypalException;
use App\Model\CartModel;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Payments\AuthorizationsGetRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;

class PaypalPayment
{


  public bool $sandbox = true;


  public function ui(CartModel $cart): string
  {

    $clientId = 'AW_v6YANStZXBNzGnkCoIqPfdlDkIT9P486nUie3OcXcdZPzcTwM7puoBfex4M2ts7Y04hkqkCG9_Gx9';
    $order = json_encode([
      'purchase_units' => [[
        'description' => 'Qissa',
        'items' => array_map(function ($product) {
          return [
            'name' => $product['productName'],
            'quantity' => $product['quantity'],
            'unit_amount' => [
              'value' => $product['price'],
              'currency_code' => 'EUR',
            ]
          ];
        }, $cart->getProductForPaypal()),
        'amount' => [
          'currency_code' => 'EUR',
          'value' => $cart->getTotal(),
          'breakdown' => [
            'item_total' => [
              'currency_code' => 'EUR',
              'value' => $cart->getTotal()
            ]
          ]
        ]
      ]]
    ]);

    return <<<HTML
    <!-- Replace "test" with your own sandbox Business account app client ID -->
    <script src="https://www.paypal.com/sdk/js?client-id={$clientId}&currency=EUR&intent=authorize"></script>
      <!-- Set up a container element for the button -->
      <div id="paypal-button-container"></div>
      <script>
        paypal.Buttons({

          createOrder: (data, actions) => {
            return actions.order.create({$order});
          },
          // Finalize the transaction after payer approval
          onApprove: async (data, actions) => {
          const authorization = await actions.order.authorize().then( function(authorization)
          {
            console.log({authorization , data})
            const authorizationId = authorization.purchase_units[0].payments.authorizations[0].id
             fetch('https://www.qisa.fr/panier/paypal-checkout', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                },
                body: JSON.stringify({authorizationId})
            })
            const status = new FormData;
            status.set('status' , 'ok');
            fetch('https://www.qisa.fr/panier' , {
            method: 'POST',
            body: status
            })
            .then((response) => response.json())
            .then((responseData) => {
            if(responseData.status == "ok"){
            window.location.href = 'https://www.qisa.fr/'
            }
            })
          })
          }
        }).render('#paypal-button-container');
      </script>
    HTML;
  }
  
 public function handle(CartModel $cart): void
  {
    $clientId = 'AW_v6YANStZXBNzGnkCoIqPfdlDkIT9P486nUie3OcXcdZPzcTwM7puoBfex4M2ts7Y04hkqkCG9_Gx9';
    $secret = 'EE4daldgEN_0_BpPtzJaNb1pdIkhwkvfUWqAiSBfIGzrIlswQhz6AUqlHp7wo4pwnrVDjjaxzIXkodBy';

    if($this->sandbox)
    {
        $environment = new SandboxEnvironment($clientId , $secret);
    }else {
        $environment = new ProductionEnvironment($clientId , $secret);
    }

    $client = new PayPalHttpClient($environment);
    $auth =  json_decode(file_get_contents('php://input'), true);

    $authorizationRequest = new AuthorizationsGetRequest($auth['authorizationId']);
   
    $authResponse = $client->execute($authorizationRequest);
   
    $amount = $authResponse->result->amount->value;
   
    if((int)$amount !== (int)$cart->getTotal())
    {
        throw new Exception();

    }


    //verifier le stock
    // verouiller le stock
    //sauvegarder les informations de l'utilisateur
    //capturer le paiement
      $orderId = $authResponse->result->supplementary_data->related_ids->order_id;
      $orderRequest = new OrdersGetRequest($orderId);
      $orderResponse = $client->execute($orderRequest);
        
     
     $request = new AuthorizationsCaptureRequest($auth['authorizationId']);
     $response = $client->execute($request);
 
      if($response->result->status !== 'COMPLETED')
      {
          throw new \Exception();
      }
      // sinon j'enregistre le paiement dans une table
  }
}
