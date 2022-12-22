<?php

namespace App\Model;

use App\Core\Application;
use App\Core\Model;
use App\Repository\BlogArticleRepository;

class BlogArticleModel extends BlogArticleRepository
{

    public string $articleTitle = '';
    public string|array $principalImage = '';
    public string $paragraphe1 = '';
    public string $createdAt = '';
    public string $updatedAt = '';
    public ?string $deletedAt = null;

    public string $paragraphe2 = '';
    public string $paragraphe3 = '';
    public string $paragraphe4 = '';
    public string $paragraphe5 = '';
    public string $paragraphe6 = '';
    public string $paragraphe7 = '';
    public string $paragraphe8 = '';
    public string $paragraphe9 = '';
    public string $paragraphe10 = '';

    public string|array $imageArticle1 = '';
    public string|array $imageArticle2 = '';
    public string|array $imageArticle3 = '';
    public string|array $imageArticle4 = '';
    public string|array $imageArticle5 = '';
    public string|array $imageArticle6 = '';
    public string|array $imageArticle7 = '';
    public string|array $imageArticle8 = '';
    public string|array $imageArticle9 = '';
    public string|array $imageArticle10 = '';



    public string $note1 = '';
    public string $note2 = '';
    public string $note3 = '';
    public string $note4 = '';
    public string $note5 = '';


    public function getInput()
    {
        return $this->paragraphe1;
    }

    public function tableName(): array
    {
        return ['blog_articles', 'article_images' , 'article_paragraphes' , 'article_notes'];
    }

public function labels(): array
{
    return [
        'articleTitle' => 'Titre de l\'article',
        'principalImage' => 'Image de l\'article',
        'paragraphe1' => 'paragraphe n*1',
        'paragraphe2' => 'paragraphe n*2',
        'paragraphe3' => 'paragraphe n*3',
        'paragraphe4' => 'paragraphe n*4',
        'paragraphe5' => 'paragraphe n*5',
        'paragraphe6' => 'paragraphe n*6',
        'paragraphe7' => 'paragraphe n*7',
        'paragraphe8' => 'paragraphe n*8',
        'paragraphe9' => 'paragraphe n*9',
        'paragraphe10' => 'paragraphe n*10',
        'imageArticle1' => 'Image n*1',
        'imageArticle2' => 'Image n*2',
        'imageArticle3' => 'Image n*3',
        'imageArticle4' => 'Image n*4',
        'imageArticle5' => 'Image n*5',
        'imageArticle6' => 'Image n*6',
        'imageArticle7' => 'Image n*7',
        'imageArticle8' => 'Image n*8',
        'imageArticle9' => 'Image n*9',
        'imageArticle10' => 'Image n*10',
        'note1' => 'note n*1',
        'note2' => 'note n*2',
        'note3' => 'note n*3',
        'note4' => 'note n*4',
        'note5' => 'note n*5',
    ];
}


    public function getImagesAttr(): array
    {
        return [
        'imageArticle1',
        'imageArticle2',
        'imageArticle3' ,
        'imageArticle4' ,
        'imageArticle5' ,
        'imageArticle6' ,
        'imageArticle7' ,
        'imageArticle8' ,
        'imageArticle9' ,
        'imageArticle10' ,

        ];
    }

    public function getNotesAttr(): array
    {
        return [
            'note1',
            'note2',
            'note3',
            'note4',
            'note5',
        ];
    }
    public function getParagraphesAttr(): array
    {
        return [
            'paragraphe2',
            'paragraphe3',
            'paragraphe4',
            'paragraphe5',
            'paragraphe6',
            'paragraphe7',
            'paragraphe8',
            'paragraphe9',
            'paragraphe10',
        ];
    }

    public function getPrincipalBlogInfoAttr(): array
    {
        return [
            'articleTitle',
            'principalImage',
            'paragraphe1',
            'createdAt',
            'updatedAt',
            'deletedAt'
        ];
    }


    public function rules(): array
    {
        return
            [
                'articleTitle' => [self::RULE_REQUIRED],
                'paragraphe1' => [self::RULE_REQUIRED],
                'principalImage' => [self::RULE_REQUIRED]
            ];
    }

    public function getImageAttribute()
    {
        return [
            $this->principalImage,
            $this->imageArticle1,
            $this->imageArticle2,
            $this->imageArticle3,
            $this->imageArticle4,
            $this->imageArticle5,
            $this->imageArticle6,
            $this->imageArticle7,
            $this->imageArticle8,
            $this->imageArticle9,
            $this->imageArticle10,
        ];
    }
    public function setCreatedAt()
    {
        $this->createdAt = date('d/m/Y H:i:s');
    }

    public function setUpdatedAt()
    {
        $this->updatedAt = date('d/m/Y H:i:s');
    }

    public function setDeletedAt()
    {
        $this->deletedAt = date('d/m/Y H:i:s');
    }
    public function save(): bool
    {

        Application::$app->files->moveFiles($this->getImageAttribute() , '/public_html/assets/images_uploads/BlogImage/');
        return parent::save();
    }

    public function getAllArticle(int $min , int $max , string $deleted)
    {
        return parent::getAllArticle( $min , $max , $deleted);
    }

    public function readArticle(int $id)
    {
        return parent::readArticle($id);

    }

    public function getArticleForUpdate(int $id)
    {
        return parent::getArticleForUpdate($id);
    }

    public function update(int $id): bool
    {
        Application::$app->files->moveFiles($this->getImageAttribute() , '/public_html/assets/images_uploads/BlogImage/');
        return parent::update($id);
    }

    public function delete(int $id , $deletedAt): bool
    {
         return parent::delete($id , $deletedAt);
    }

    public function restore(int $id): bool
    {
        return parent::restore($id); // TODO: Change the autogenerated stub
    }
    public function countArticle()
    {
        return parent::countArticle(); // TODO: Change the autogenerated stub
    }

}