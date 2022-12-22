
<?php
$this->title = 'liste membres';
$this->css = 'admin_member_interface';
$this->javascript = 'accueil';

?>
<main class="container container__table">
    <button class="btn btn__primary btn__admin"><a href="/admin/archive-article" class="admin__link">Voir les articles archiver</a></button>

    <table class="table">
        <thead>
        <tr class="th__container">
            <th scope="col">ID</th>
            <th scope="col">Nom</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
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
                <td data-label="Nom :"><?= $name  ?></td>
                <td data-label="Email :"><?= $email  ?></td>
                <td data-label="Role :">
                    
                        <div>
                            <p style="display: inline"><?= $role ?></p>
                        </div>

                    </td>
                <td data-label="créer le :"><?= $createdAt ?></td>
                <td data-label="modifié le :"><?= $updatedAt ?></td>
                <td></td>
                <td data-label="action :"><a href="/admin/modifier-utilisateur/<?= $data['id']  ?>"><img src="/assets/images_uploads/divers/refresh.png" style="width: 30px"></a>
                    <a href="/admin/delete-article/<?=$data['id']?>"> <img src="/assets/images_uploads/divers/remove.png" style="width: 30px"></a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>




</main>
