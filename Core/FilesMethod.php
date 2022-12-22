<?php

namespace App\Core;

class FilesMethod
{

    public function moveFiles(array $files , string $directoryPath)
    {

        foreach($files as $key => $value)
        {

            if(!file_exists(Application::$ROOT_DIR . $directoryPath))
            {
                mkdir(Application::$ROOT_DIR . $directoryPath, 077 , true);

            }
            if(!empty($value['name']))
            {

                move_uploaded_file($value['tmp_name'] , Application::$ROOT_DIR . $directoryPath . $value['name']);
            }

        }
    }

    public function getExtension(string $fileName)
    {

            $array = explode('.', $fileName);
            array_shift($array);

            return '.' . $array[0];
    }
}