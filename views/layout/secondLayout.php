<?php
use App\Core\Application;
?>

<!doctype html>
<html lang="en">
<head>
<?php
Application::$app->cookies->handleGoogleCookies();
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $this->meta ?>">
    <link rel="stylesheet" href="/css/<?= $this->css?>.css">
    <title><?=$this->title?></title>
    <link rel="shortcut icon" href="/assets/images_uploads/divers/qissa2.png">

</head>
<body>
<header class="head">
    <nav>
        <h1 class="nav_title_principal" id="nav_title_principal"><a href="/"><span class="span__h1">QI</span>SSA</a><i class="fa-solid fa-bars menu__burger "></i></h1>
        <div class="menu__mb">

            <h1 class="nav__title"><a href="/"><span class="span__h1">QI</span>SSA</a><i id="leave__mb" class="fa-solid fa-xmark fa-xl"></i></h1>
            <a class="mb__link" href="/">BLOG</a>
            <a class="mb__link" href="/boutique">BOUTIQUE</a>
            <a class="mb__link" href="/panier">PANIER</a>
            <?php
            if(Application::isGuest()) {
            ?>
            <a class="mb__link" href="/register">INSCRIPTION</a>
            <a class="connection__mb" href="/login"><i class="fa-regular fa-user fa-xl"></i>se connecter</a>
            <?php } else{ ?>
                <a class="btn btn__primary btn__logout " href="/logout" >se deconnecter</a>
            <?php } ?>

        </div>


        <div class="container nav__container">
            <h1 class="title_md_lg"><a href="/"><span class="span__h1">QI</span>SSA</a></h1>
            <ul class="nav__menu">
                <li class="nav__selection"><a href="/">blog</a></li>
                <li class="nav__selection"><a href="/boutique">Boutique</a></li>
                <li class="nav__selection"><a href="/panier">Panier</a></li>

          
                <?php
                    if(Application::isAdmin()){
                ?>
                        <ul class="">
                            <li class="nav__selection admin__button">
                                <a type="button">admin</a><img src="/assets/images_uploads/divers/arrow.svg" class="arrow__down">
                            </li>

                            <ul class="admin__menu visibility__none">
                                <li><a href="/admin/create-product" class="admin__btn">ajouter un produit</a></li>
                                <li><a href="/admin/create-blog-article" class="admin__btn ">ajouter un article</a></li>
                                <li><a href="/admin/voir-produits" class="admin__btn">voir les produits</a></li>
                                <li><a href="/admin/voir-utilisateurs" class="admin__btn">voir les utilisateurs</a></li>
                                <li><a href="/admin/voir-articles" class="admin__btn">voir les articles</a></li>
                                <li><a href="/admin/voir-commandes" class="admin__btn">voir les commandes</a></li>
                            </ul>
                        </ul>




                <?php
                }
                ?>



                <?php
                if(Application::isGuest()) {
                    ?>
                    <li class="nav__selection"><a href="/register">inscription</a></li>
                    <li><a class="btn btn__primary " href="/login">se connecter</a></li>
                <?php } else{ ?>
                    <a class="btn btn__primary btn__logout " href="/logout" >se deconnecter</a>
                <?php } ?>
            </ul>

        </div>
    </nav>


</header>

  
<?php if (Application::$app->session->getFlash('success')) : ?>
       
       <div id="flash_message" class="flash__message">
           <p><?php echo Application::$app->session->getFlash('success') ?></p>
           <i id="flash_message_close" class="fa-solid fa-circle-xmark flash_message_close"></i>
       </div>
       <?php endif ?>


    {{content}}




<script src="/javascript/<?= $this->javascript ?>.js"></script>
  </body>
</html>