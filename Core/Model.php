<?php

namespace App\Core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = "unique";
    public const RULE_PREG_TEXT = "pregText";
    public const RULE_EMAIL_DOMAIN = "domain";
    public const RULE_MAX_REQUEST = "max_request";
    public const RULE_FILES = "files";

        public function loadData($data , ?array $files = null)
        {
            if(is_array($data))
            {
            foreach($data as $key => $value)
            {

                if(property_exists($this, $key))
                {
                    if(is_array($this->{$key}))
                    {
                        $this->{$key}['name'] = $value;
                    }
                    $this->{$key} = $value;

                }
            }
            if(isset($files) && $files != null)
            {
                foreach($files as $key => $value)
                {

                    if(property_exists($this, $key))
                    {

                        $this->{$key} = $value;

                    }
                }
            }
            }
        }



        abstract public function rules(): array;



        public function getLabels($attribute)
        {
            return $this->labels()[$attribute] ?? $attribute;
        }
 
        public array $errors = [];

        public function validate()
        {

            foreach($this->rules() as $attribute => $rules)
            {

                $value = $this->{$attribute} ?? '';
               
                foreach($rules as $rule)
                {
                    $ruleName = $rule;
                    if(!is_string($ruleName))
                    {
                        $ruleName = $rule[0];

                    }

                    if($ruleName === self::RULE_REQUIRED && !$value)
                    {
                        $this->AddErrorOnRule($attribute, self::RULE_REQUIRED);
                    }
                    if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    {
                        $this->AddErrorOnRule($attribute, self::RULE_EMAIL);
                    }
                    if($ruleName === self::RULE_EMAIL_DOMAIN && !empty($value))
                    {
                        $email = $value;
                        $extension = explode('@' , $email);
                        $domain = array_pop($extension);
                        if(!checkdnsrr($domain))
                        {
                            $this->AddErrorOnRule($attribute , self::RULE_EMAIL_DOMAIN);
                        }
                    }
                    if($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    {
                        $this->AddErrorOnRule($attribute, self::RULE_MIN, $rule);
                    }

                    if($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    {
                        $this->AddErrorOnRule($attribute, self::RULE_MAX, $rule);
                    }
                    if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']})
                    {
                        $rule['match'] = $this->getLabels($rule['match']);
                        $this->AddErrorOnRule($attribute, self::RULE_MATCH, $rule);
                    }
                    if($ruleName === self::RULE_FILES)
                    {
                       if(!is_array($value))
                       {
                           if(!$value)
                           {
                               $this->AddErrorOnRule($attribute , self::RULE_FILES);
                           }
                       }else {
                           array_pop($value);
                           if(!$value['name'])
                           {
                               $this->AddErrorOnRule($attribute, self::RULE_FILES);
                           }
                       }



                    }
                        if($ruleName === self::RULE_UNIQUE)
                    {
                        $className = $rule['class'];
                        $uniqueAttr = $rule['attribute'] ?? $attribute;
                        $tableName = $className::tableName();
                        if(is_array($tableName))
                        {
                            $tableName = $tableName[0];
                        }

                        $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                        $statement->bindValue(":attr" , $value);
                        $statement->execute();
                        $record = $statement->fetchObject();
                        if($record)
                        {
                            $this->AddErrorOnRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabels($attribute)]);
                        }
                    }

                    if($ruleName === self::RULE_PREG_TEXT)
                    {
                        $text = $value;
                        if(!preg_match('/^[a-zA-Z- ]+$/',$text))
                        {
                            $this->AddErrorOnRule($attribute , self::RULE_PREG_TEXT , ['text' => $this->getLabels($attribute)]);
                        }
                    }
                    if($ruleName === self::RULE_MAX_REQUEST)
                    {
                        $value = $rule['value'];
                        if($value > $rule['max_request'])
                        {
                            $this->addErrorOnRule('max_request' , self::RULE_MAX_REQUEST);
                        }


                    }
                }
            }

            return empty($this->errors);
        }


        private function AddErrorOnRule(string $attribute, string $rule , $params = [])
        {

            $message = $this->errorMessages()[$rule] ?? '';
            foreach($params as $key => $value )
            {
                $message = str_replace("{{$key}}", $value , $message );
            }
            $this->errors[$attribute][] = $message;
        }


    public function AddError(string $attribute, string $message)
    {

        $this->errors[$attribute][] = $message;
    }

        public function errorMessages(): array
        {
            return [
                self::RULE_REQUIRED => 'ce champ est requis',
                self::RULE_EMAIL => 'l\'adresse e-mail doit être valide',
                self::RULE_MIN => 'ce champ doit contenir au minimum {min} caractères',
                self::RULE_MAX => 'ce champ doit contenir au maximum {max} caractères',
                self::RULE_MATCH => 'ce champ doit correspondre au champ {match}',
                self::RULE_UNIQUE => 'Le champ {field} que vous avez essayez d\'ajouter existe déjà',
                self::RULE_EMAIL_DOMAIN => 'Cette adresse email est inéxistante.',
                self::RULE_PREG_TEXT => 'Ce champs {text} ne peut contenir que des alphabets.',
                self::RULE_MAX_REQUEST => 'Vous atteint le maximum de demande pour la journée veuillez réessayer dans 24h',
                self::RULE_FILES => 'Ajouter une image principal (obligatoire).',
                
            ];
        }

        public function hasError($attribute)
        {

            return $this->errors[$attribute] ?? false;
        }

        public function getFirstError($attribute)
        {

            return $this->errors[$attribute][0] ??  false;
        }

}