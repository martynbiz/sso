<?php
namespace App\Controller;

use App\Model\User;

class HomeController extends BaseController
{
    public function index($request, $response, $args)
    {
        return $this->render('home/index');
    }
}
