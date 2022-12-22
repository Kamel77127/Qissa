<?php
namespace App\Model;
use App\Core\UserModel;

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const ROLE_USER = '[ROLE_USER]';
    const ROLE_ADMIN = '[ROLE_ADMIN]';
    const ROLE_MOD = '[ROLE_MOD]';



    public string $name = '';
    public string $email = '';
    public int $status = self::STATUS_INACTIVE;
    public string $password = '';
    public string $passwordConfirm = '';
    public string $role = '';

    public string $createdAt = '';
    public string $updatedAt = '';
    public ?string $deletedAt = '';


    public static function tableName(): string
    {
        return 'users';
    }


    public function save()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->role = self::ROLE_USER;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
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


    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED , self::RULE_PREG_TEXT],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL,self::RULE_EMAIL_DOMAIN, [self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'passwordConfirm' => [[self::RULE_MATCH, 'match' => 'password']],


        ];
    }

    public static function attributes(): array
    {
        return ['name','email', 'password', 'status' , 'role' , 'createdAt' , 'updatedAt', 'deletedAt'];
    }

    public function labels(): array
    {
        return [
            'name' => 'Nom',
            'email' => 'Email',
            'password' => 'Mot de passe',
            'passwordConfirm' => 'mot de passe',
        ];
    }


    public static function primaryKey(): string
    {
        return 'id';
    }


    public function update(int $id)
    {
        $this->status = self::STATUS_ACTIVE;
        return parent::update($id);
    }

}