
<?php
$this->title = 'liste des articles';
$this->css = 'admin_interface';
$this->javascript = 'accueil';

?>
<main class="container container__table">
    <button class="btn btn__primary btn__admin"><a href="/admin/archive-article" class="admin__link">Voir les articles archiver</a></button>

    <table class="table">
        <thead>
        <tr class="th__container">
            <th scope="col">numéro de l'article</th>
            <th scope="col">Titre de l'article</th>
            <th scope="col">Image principal</th>
            <th scope="col">créer le :</th>
            <th scope="col">modifié le :</th>
            <th scope="col">archivé le :</th>
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

                <td data-label="Article :"><a href="/blog-page/<?= $id ?>"><?= $articleTitle  ?></a></td>
                <td data-label="Image :">
                <a href="/blog-page/<?= $id ?>"><img src="/assets/images_uploads/BlogImage/<?= $principalImage ?>" class="art__img" style="width: 50px; height:50px; border-radius: 30px"></a></td>
                <td data-label="créer le :"><?= $createdAt ?></td>
                <td data-label="modifié le :"><?= $updatedAt ?></td>
                <td></td>
                <td data-label="action :"><a href="/admin/modifier-article/<?= $data['id']  ?>"><img src="/assets/images_uploads/divers/refresh.png" style="width: 30px"></a>
                    <a href="/admin/delete-article/<?=$data['id']?>"> <img src="/assets/images_uploads/divers/remove.png" style="width: 30px"></a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

<div class="pagination__container">

<?php if ($currentPage > 1) :  ?>
    <a class="btn btn__primary btn__pagination" href="/admin/voir-articles/page/<?= $currentPage - 1 ?>">Page précedente</a>
<?php endif; ?>

<?php  ;
if ($currentPage < $pages) :  ?>
    <a class="btn btn__primary btn__pagination" href="/admin/voir-articles/page/<?= $currentPage + 1 ?>">Page suivante</a>
<?php endif; ?>
</div>



</main>
