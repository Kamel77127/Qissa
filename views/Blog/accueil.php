<?php
$this->title = 'Accueil';
$this->css = 'accueil';
$this->javascript = 'accueil';
$this->meta = "Qissa, maison d'édition de livre orientaux et sur l'histoire et le monde islamique."
?>
<main class="container main__container">
    <article id="citation">
        <figure><img id="baghdad" src="/assets/images_uploads/divers/baghdad.jpg" alt=""></figure>
        <p>‘Celui qui connaît l’Histoire, large est son esprit & minimes sont ses erreurs.’
        </p>
        <p>– Imâm ash-Shafi’i –</p>

    </article>
    <section class="article__container">
    <?php while ($data = $rows->fetch(PDO::FETCH_ASSOC)) {
        extract($data); ?>



            <article class="article__flex">

                <figure><a href="/blog-page/<?= $id ?>"><img src="/assets/images_uploads/BlogImage/<?= $principalImage ?>" alt="Image d'article , <?= $articleTitle ?>"></a></figure>
                <div class="text__container">
                    <h5 class="article__title">
                        <?= $articleTitle ?>
                    </h5>

                    <p class="created__at">publié le <span class="date"><?= $createdAt ?></span></p>


                    <p class="little__desc"><?= substr($paragraphe1, 0, 150) ?>
                        ...[<a href="/blog-page/<?= $id ?>" class="link_to_blog">lire la suite</a>]</p>
                </div>
            </article>
            <hr class="article__separator">
            <?php } ?>
            <div class="pagination__container">

            <?php if ($currentPage > 1) :  ?>
                <a class="btn btn__primary btn__pagination" href="/page/<?= $currentPage - 1 ?>">Page précedente</a>
            <?php endif; ?>

            <?php  ;
            if ($currentPage < $pages) :  ?>
                <a class="btn btn__primary btn__pagination" href="/page/<?= $currentPage + 1 ?>">Page suivante</a>
            <?php endif; ?>
            </div>
        </section>

</main>