
<?php
use App\Core\Application;

$this->title = 'inscription';
$this->css = 'register_login';
$this->javascript = 'accueil';
Application::$app->csrf->createToken();

$form = App\Core\Form\Form::begin('', "post")  
?>

    <?php echo $form->field($model, 'name' , 'user_name' , 'user__name');
      echo new \App\Core\Form\CsrfInputField($model ,'' , '' , '');
     echo $form->field($model, 'email' ,'user_email' , 'user__email');
     echo $form->field($model, 'password' , 'user_password' , 'user__password')->passwordField();
     echo $form->field($model, 'passwordConfirm' , 'password_confirm' , 'password__confirm')->passwordField()?>
    <button type="submit" class="btn btn__primary btn__form">envoyer</button>

<?php App\Core\Form\Form::end();?>


