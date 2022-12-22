<?php
use App\Core\Application;

$this->title = 'création d\'articles';
$this->css = 'create_article';
$this->javascript = 'admin_create_page';
Application::$app->csrf->createToken();

$form = \App\Core\Form\Form::begin('' , 'post');
echo new \App\Core\Form\CsrfInputField($model ,'' , '' , '');
echo $form->field($model , 'articleTitle' , 'article_title' , 'article__title');
echo $form->field($model , 'principalImage' , 'principal_image' , 'principal__image')->fileInput();
$i = 0;
while($i < 10)
{
    $i++;
    echo new \App\Core\Form\TextAreaField($model ,"paragraphe$i" , "paragraphe_$i", "paragraphe__$i" );
    echo $form->field($model , "imageArticle$i" , "image_article$i" , "image__article$i")->fileInput();
}
$i= 0;
while($i < 5)
{
    $i++;
    echo $form->field($model , "note$i" , "note_$i" , "note__$i");

}

echo '<button type="submit" class="btn btn__primary btn_send_form">créer l\'article </button>';



?>



