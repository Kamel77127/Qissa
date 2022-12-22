<?php

use App\Core\Application;
use Mezia\Mvc\Service\PaypalPayment;

Application::$app->csrf->createToken();

$this->title = 'panier';
$this->css = 'cart';
$this->javascript = 'cart';
$this->meta = "Page panier";
$total = null;
$subTotal = null;

?>
<main class="container cart__container">
    <form action="" method="post">
        <?= Application::$app->csrf->csrfInput(); ?>
        <table class="table">
            <thead>
                <tr class="th__container">
                    <th></th>
                    <th>Produit:</th>
                    <th>Image:</th>
                    <th>Prix:</th>
                    <th>Quantité:</th>
                    <th>Sous-total:</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($data = $rows->fetch(PDO::FETCH_ASSOC)) {
                    extract($data);
                    $total += $subPrice;
                    $subTotal += $subPrice;
                ?>
                    <tr class="td__container">

                        <td>
                            <button type="submit" class="remove__btn" name="remove" id="remove_btn" value="<?= $productId ?>">
                                <img src="/assets/images_uploads/divers/remove.png" id="remove_img" class="remove__img" alt="">
                            </button>
                        </td>

                        <td data-label="Produit"><a href="/boutique/page-produit/<?= $productId ?>"><?= $productName  ?></a></td>
                        <td data-label="Image"><a href="/boutique/page-produit/<?= $productId ?>"><img src="/assets/images_uploads/ProductImg/<?= $image ?>" class="cart__img"></a></td>
                        <td data-label="Prix"><?= $price ?>€</td>
                        <td data-label="Quantité">
                                <input type="number" name="<?= $productId  ?>" step="1" min="1" max="16" size="4" value="<?= $quantity  ?>">
                            </td>
                        <td data-label="Sous-total"><?= $subPrice  ?>€</td>

                    </tr>
                <?php } ?>


            </tbody>
        </table>

        <div class="update__cart">
            <button type="submit" name="updateCart" class="btn btn__primary btn__update-cart">METTRE à JOUR LE PANIER</button>
        </div>





        <div class="sub__container">
            <section>

                <h4 class="interest__">Vous serez peut-être intéressé par ...</h4>

                <section class="product__container">

                    <?php
                    while ($data = $rand->fetch(PDO::FETCH_ASSOC)) {
                        extract($data);
                       
                    ?>
                        <article class="article__selection">

                        <a href="/boutique/page-produit/<?= $id ?>"><img class="article__img" src="/assets/images_uploads/ProductImg/<?= $principalImage ?>" alt=""></a>

                        <a href="/boutique/page-produit/<?= $id ?>"><h4 class="book__tile"><?= $name ?> </h4></a>

                            <p> <?= $price ?> €</p>

                            <form action="" method="post" class="add_to_cart">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Application::$app->csrf->getToken() ?>">

                                <input type="hidden" name="productId" value="<?= $id ?>">
                                <input type="hidden" name="price" value="<?= $price ?>">
                                <input type="hidden" name="name" value="<?= $name ?>">
                                <input type="hidden" name="image" value="<?= $principalImage ?>">

                                <button type="submit" name="add_to_cart" class="btn btn__add-to-cart">ajouter au panier</button>
                            </form>
                        </article>
                    <?php } ?>

                </section>

            </section>
            <!-- INTEREST -->




            <!-- Payment Table -->
            <section>

                <h4 class="total__title">Total Panier</h4>
                <table class="expedition__table">
                    <thead class="expedition__thead">
                        <tr class="expedition__tr" class="th__container">
                            <th class="expedition__th">Sous-total:</th>
                            <th class="expedition__th">Expédition:</th>
                            <th class="expedition__th">Total:</th>
                        </tr>
                    </thead>
                    <tbody class="expedition__body">
                        <tr class="expedition__tr" class="td__container">
                            <td class="expedition__td" data-label="Sous-total"><?= $subTotal ?>€</td>
                            <td class="expedition__td" data-label="Expédition:" class="expedition__td">

                                <input type="radio" name="expedition" value="colissimo" class="attribute__inline">
                                <p class="attribute__inline">Colissimo(domicile):</p>

                            </td>
                            <td class="expedition__td">

                                <input type="radio" name="expedition" value="mondialRelay" class="attribute__inline">

                                <p class="attribute__inline">Mondial Relay(point-relais):</p>
                                <p>Point-relais souhaité à indiquer en note/commentaire ou sélection automatique du point-relais le plus proche du domicile</p>
                                <br>
                                <p></p>

                            </td>

                            <td class="expedition__td" data-label="Total:"><?= $total ?> €</td>

                        </tr>

                    </tbody>
                </table>
                <div class="payment__container">

                    <div class="btn__paypal" id="paypal-button-container"></div>


                </div>



        </div>




        </section>




        </div>
    </form>

</main>
 <?php

            $paypal = new PaypalPayment();
            echo $paypal->ui($model);
?> 
