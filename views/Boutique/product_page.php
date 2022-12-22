<?php
$this->css = 'product_page';
$this->javascript = 'product';
$this->meta = "";
?>

<main class="container">
    <?php while ($data = $row->fetch(PDO::FETCH_ASSOC)) {
        extract($data);
        $this->title = $name;
        $this->meta = substr($description , 0 , 40);?>
        <section class="contain__items">
  
            <div class="contain__img">
                <figure><img src="/assets/images_uploads/ProductImg/<?= $principalImage ?>" alt="image du livre <?= $name ?>" class="primary__img"></figure>


                <?php
                $i = 0;
                while ($i < 5) {
                    $i++;
                    $desc =substr($description , 80);
                    if ($data["img$i"] !== '' && !is_null($data["img$i"])) {
                ?>
                        <img class="secondary__img" src="/assets/images_uploads/ProductImg/<?= $data["img$i"] ?>" alt="image du livre <?= $name ?>">
                <?php }
                } ?>

            </div>

            <div class="product__information">
                <h4 class="product__title"><?= $name ?></h4>
                <hr class="hr__title">
                <p class="center">Prix : <?= $price ?> €</p>
                <p class="center">Nombre de pages : <?= $pages ?> pages</p>
                <p class="center">Auteur : <?= $author ?></p>


                <form action='' method="post">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Application::$app->csrf->getToken() ?>">
                    <input type="hidden" name="productId" value="<?= $data['id'] ?>">
                    <input type="hidden" name="price" value="<?= $price ?>">
                    <input type="hidden" name="name" value="<?= $name ?>">
                    <input type="hidden" name="image" value="<?= $principalImage ?>">
                    <button type="submit" name="add_to_cart" class="add_to_cart">ajouter au panier</button>
                </form>

            </div>


        </section>

        <div class="toggle__desc">
            <div>
                <h3 id="toggle" class="desc"> description</h3>
                <hr id="hr1">
                <hr id="md_lg1">
            </div>
            <div>
                <h3 id="toggle" class="inf">Informations complémentaires</h3>
                <hr id="hr2">
                <hr id="md_lg2">
            </div>
            <div>
                <h3 id="toggle" class="av">avis</h3>
                <hr id="hr3">
                <hr id="md_lg3">
            </div>

        </div>

        <hr class="container" id="hr4">

        <div class="description">
            <h4>description</h4>
            <p><?= $description ?></p>
        </div>

        <div class="informations">
            <h4>information</h4>
            <div class="inf__weight">
                <p>POIDS</p>
                <p>0.8kg</p>
            </div>
        </div>


        <div class="avis">
            <h4>avis</h4>
            <p></p>
        </div>
    <?php } 
    ?>

</main>