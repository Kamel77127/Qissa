<?php

namespace App\Controllers;

use App\Core\Controller;

class Divers extends Controller
{


    public function legalMentions()
    {
        $this->setLayout('/secondLayout');
        return $this->render('/legal_mention');
    }

    public function cgv()
    {
        $this->setLayout('/secondLayout');
        return $this->render('/cgv');
    }
}