<?php

use App\Core\Application;

$this->title = 'connexion';
$this->css = 'register_login';
$this->javascript = 'accueil';
Application::$app->csrf->createToken();

?>


<?php

$form = App\Core\Form\Form::begin('' , "POST")
?>

<?php

echo new \App\Core\Form\CsrfInputField($model ,'' , '' , '');
echo $form->field($model, 'email' , 'email_input' , 'email__input') ?>
<?php echo $form->field($model, 'password' , 'password_input' , 'password__input')->passwordField()?>

<button type="submit" class="btn btn__primary">se connecter</button>

<?php 

App\Core\Form\Form::end();?>

