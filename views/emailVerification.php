<?php

use App\Core\Application;

echo \App\Core\Application::$app->session->getFlash('success');

Application::$app->response->redirect('/');