<?php

namespace App\Http\Controllers;

use App\Http\Router;
use App\Utils\DB;

class Controller extends DB
{

    public function __construct()
    {
        if(!isset($_SESSION['user']) && $_GET['url'] !== 'login' && $_GET['url'] !== 'register') {
            Router::redirect('/login');
        }
    }

    public function checkAdmin() {
        if(isset($_SESSION['redirect_params']) && $_SESSION['redirect_params'] === 'redirected') {
            return false;
        }

        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['admin'] != 1) {
                Router::redirect('/user/homework', 'redirected');
            }
        }
    }

    public function checkUser() {
        if($_SESSION['redirect_params'] == 'redirected') {
            return false;
        }

        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['admin'] == 1) {
                Router::redirect('/admin/homework', 'redirected');
            }
        }
    }

    protected function view($content = [], $view) {

        $content['errors'] = !empty($_SESSION['redirect_params']) ? $_SESSION['redirect_params'] : false;
        $view = str_replace('.', '/', $view);

        require_once( __DIR__ . '/../../../views/header.php');
        require_once( __DIR__ . '/../../../views/' . $view . '.php');
        require_once( __DIR__ . '/../../../views/footer.php');
    }
}
