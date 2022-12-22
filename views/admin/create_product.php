<main>
<?php
use App\Core\Application;
Application::$app->csrf->createToken();

$this->title = "création de produit";
$this->css ="create_product";
$this->javascript = "admin_create_page";

$form = \App\Core\Form\Form::begin('' , 'post');
echo new \App\Core\Form\CsrfInputField($model ,'' , '' , '');

 echo $form->field($model , 'name' , 'book_title' , 'book__title');
 echo $form->field($model , 'author' , 'book_author' , 'book__author');
 echo $form->field($model , 'principalImage' , 'book_cover' , 'book__cover')->fileInput();
 echo $form->field($model , 'price' , 'book_price' , 'book__price')->numberInput();
 echo new \App\Core\Form\TextAreaField($model , 'description' , 'book_description' , 'book__description');
 echo $form->field($model , 'pages' , 'product_pages' , 'product__pages')->numberInput();
 echo $form->field($model , 'stock' , 'product_stock' , 'product__stock')->numberInput();
 $i= 0;
 while($i < 5)
 {
     $i++;
     echo $form->field($model , "img$i" , 'book_img' , 'book__img')->fileInput();
 }
 ?>

 <button type="submit" class="btn btn__primary btn_send_form">créer le produit</button>

<?php
\App\Core\Form\Form::end();
?>

</main>



