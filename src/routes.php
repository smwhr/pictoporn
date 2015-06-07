<?php

$app->get('/', 'Controller\FrontController::indexAction')->bind('home');
$app->get('/search', 'Controller\FrontController::searchAction')->bind('search');
$app->get('/image', 'Controller\FrontController::proxyimageAction')->bind('image');
$app->get('/credits', 'Controller\FrontController::creditsAction')->bind('credits');
