
<?php
$this->title = 'Archive produits';
$this->css = 'admin_interface';
$this->javascript = 'accueil';

?>
<main class="container container__table">

    <table class="table">
        <thead>
        <tr class="th__container">
            <th scope="col">ID</th>
            <th scope="col">Nom produit</th>
            <th scope="col">Image principal</th>
            <th scope="col">créer le :</th>
            <th scope="col">modifié le :</th>
            <th scope="col">mis en archivre le :</th>
            <th scope="col">Action</th>


        </tr>
        </thead>
        <tbody>
        <?php
        while ($data = $rows->fetch(PDO::FETCH_ASSOC))
        {extract($data);

            ?>
            <tr class="td__container">

                <td data-label="ID :">
                    <p><?= $id ?></p>
                </td>

                <td data-label="Produit :"><span><a href="/boutique/page-produit/<?=$id?>"><?= $name  ?></a></span></td>
                <td data-label="Image :"><span><img src="/assets/images_uploads/ProductImg/<?= $principalImage ?>" class="cart__img" style="width: 50px; border-radius: 30px"></span></td>
                <td data-label="créer le :"><span><?= $createdAt ?></span></td>
                <td data-label="modifié le :"><span><?= $updatedAt ?></span></td>
                <td data-label="modifié le :"><span><?= $deletedAt ?></span></td>

                <td><a href="/admin/modifier-produit/<?= $id  ?>"><img src="/assets/images_uploads/divers/refresh.png" style="width: 30px"></a>
                    <a href="/admin/restore-product/<?=$id?>"> <img src="/assets/images_uploads/divers/check.png" style="width: 30px"></a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>



