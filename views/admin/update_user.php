<?php
use App\Core\Application;
Application::$app->csrf->createToken();

$this->title = 'modifier l\'utilisateur';
$this->css = 'create_product';
$this->javascript = 'admin_create_page';
?>


<?php
$form = App\Core\Form\Form::begin('', "post");
echo new \App\Core\Form\CsrfInputField($model ,'' , '' , '');

?>
<div class="radio__container">
    <p style="display: inline">Admin</p>

    <?php
    if($model->role === "[ROLE_ADMIN]"){
        ?>
        <input type="radio" name="role" checked size="10" value="[ROLE_ADMIN]" style="display: inline">
        <?php
    }else {
        ?>
        <input type="radio" name="role" size="10" value="[ROLE_ADMIN]"  style="display: inline">
        <?php
    }
    ?>
</div>
    <div class="radio__container">
        <p style="display: inline">Modérateur</p>

        <?php
        if($model->role === "[ROLE_MOD]"){
            ?>
            <input type="radio" name="role" checked size="10" value="[ROLE_MOD]" style="display: inline">
            <?php
        }else {
            ?>
            <input type="radio" name="role" size="10" value="[ROLE_MOD]" style="display: inline">
            <?php
        }
        ?>
    </div>

    <div class="radio__container">
        <p style="display: inline">Utilisateur</p>

        <?php
        if($model->role === "[ROLE_USER]"){
            ?>
            <input type="radio" name="role" checked size="10" value="[ROLE_USER]" style="display: inline">
            <?php
        }else {
            ?>
            <input type="radio" name="role" size="10" value="[ROLE_USER]" style="display: inline">
            <?php
        }
        ?>
    </div>


    <button type="submit" class="btn btn__logout">envoyer</button>

<?php App\Core\Form\Form::end();?>

