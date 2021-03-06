<?php
// Routes

$app->get('/', '\App\Controller\HomeController:index')->setName('home');

// session routes
$app->get('/login', '\App\Controller\SessionController:login')->setName('login');
$app->post('/login', '\App\Controller\SessionController:post')->setName('login_post');
$app->get('/logout', '\App\Controller\SessionController:logout')->setName('logout');
$app->delete('/logout', '\App\Controller\SessionController:delete')->setName('logout_post');

// user routes
$app->get('/register', '\App\Controller\UsersController:register')->setName('register');
$app->post('/register', '\App\Controller\UsersController:post')->setName('register_post');

// oauth routes
$app->get('/oauth/authorize', '\App\Controller\OauthController:authorize')->setName('oauth_authorize');
$app->post('/oauth/access_token', '\App\Controller\OauthController:accessToken')->setName('oauth_access_token');
$app->get('/oauth/user', '\App\Controller\OauthController:user')->setName('oauth_user');
