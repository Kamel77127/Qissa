





<?php
$this->title = 'liste des commandes';
$this->css = 'admin_order_interface';
$this->javascript = 'accueil';

?>
<main class="container container__table">

    <table class="table">
        <thead>
        <tr class="th__container">
            <th scope="col">ID utilisateur</th>
            <th scope="col">ID produit</th>
            <th scope="col">quantité</th>
            <th scope="col">total</th>
            <th scope="col">créer le :</th>
            <th scope="col">modifié le :</th>
            <th scope="col">Action</th>


        </tr>
        </thead>
        <tbody>
        <?php
        while ($data = $rows->fetch(PDO::FETCH_ASSOC))
        {extract($data);

            ?>
            <tr class="td__container">

                <td data-label="ID utilisateur :">
                    <p><?= $user_id ?></p>
                </td>

                <td data-label="ID produit :"><a href="/boutique/page-produit/<?=$id?>"><?= $productId ?></a></td>
                <td data-label="quantité :"><?= $quantity ?></td>
                <td data-label="total :"><?= $total ?> €</td>
                <td data-label="créer le :"></td>
                <td data-label="modifié le :"></td>

               <td><a href="#"><img src="/assets/images_uploads/divers/refresh.png" style="width: 30px"></a>
                    <a href="#"> <img src="/assets/images_uploads/divers/remove.png" style="width: 30px"></a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>




</main>







