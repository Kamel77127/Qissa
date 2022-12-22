<?php

use App\Core\Application;

$this->title = 'Boutique';
$this->css = 'shop';
$this->javascript = 'shop';
$this->meta = 'Boutique , avec une large gamme de livre pour tout les goûts';
Application::$app->csrf->createToken();
?>

    <main class="article__container">
        
        <?php while ($data = $rows->fetch(PDO::FETCH_ASSOC)) {
            extract($data);
        ?>
            <article class="product__article">
                <a href="/boutique/page-produit/<?= $id ?>">
                    <figure><img src="/assets/images_uploads/ProductImg/<?= $principalImage ?>" alt="Livre, <?= $name ?>"></figure>
                </a>
                <a href="/boutique/page-produit/<?= $id ?>">
                    <h3 class="product__title"><?= $name ?></h3>
                </a>
                <p class="product__price"><?= $price ?>€</p>
                <form action='' method="post">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Application::$app->csrf->getToken() ?>">
                    <input type="hidden" name="productId" value="<?= $id ?>">
                    <input type="hidden" name="price" value="<?= $price ?>">
                    <input type="hidden" name="name" value="<?= $name ?>">
                    <input type="hidden" name="image" value="<?= $principalImage ?>">
                    <button type="submit" name="add_to_cart" class="add_to_cart">ajouter au panier</button>
                </form>


            </article>
        <?php } ?>

        </main>


    <div class="pagination__container" style="margin-top:5rem">

        <?php if ($currentPage > 1) :  ?>
            <a class="btn btn__pagination" href="/boutique/page/<?= $currentPage - 1 ?>">Page précedente</a>
        <?php endif; ?>

        <?php if ($currentPage < $page) :  ?>
            <a class="btn btn__pagination" href="/boutique/page/<?= $currentPage + 1 ?>">Page suivante</a>
        <?php endif; ?>
    </div>
