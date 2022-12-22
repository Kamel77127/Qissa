<?php
$this->css = 'article_page';
$this->javascript = 'article_page';
$this->meta = "Qissa, maison d'édition de livre orientaux et sur l'histoire et le monde islamique."
?>


<main class="container">
    <section class="article__container">
        <?php
        while ($data = $row->fetch(PDO::FETCH_ASSOC)) {
            extract($data);
            $this->title = $articleTitle;
        ?>
            <h2 class="article__title"><?= $articleTitle ?></h2>
            <hr>
            <br>
            <br>

            <?php
            $i = 0;

            while ($i < 10) {
                $i++; ?>
                <p class="description"><?= $data["paragraphe$i"] ?></p>

                <?php if ($data["imageArticle$i"] !== '') { ?>
                    <img src="/assets/images_uploads/BlogImage/<?= $data["imageArticle$i"] ?>" alt="<?= $articleTitle . "," . $data["imageArticle$i"]  ?>">
                <?php } ?>
            <?php } ?>

            <?php
            $i = 0;
            while ($i < 5) {
                $i++;
                if ($data["note$i"] !== '') {
            ?>
                    <h3>Notes</h3>
                    <hr>
                    <br>
                    <p><?= "$i : " . $data["note$i"] ?> </p>
                    <br>
            <?php }
            } ?>
        <?php } ?>
    </section>

</main>