<?php
namespace App\Http\Controllers;

use App\Http\Router;

class HomeController extends Controller
{
    public function index() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['admin'] == 1) {
                Router::redirect('admin/homework');
            } else {
                Router::redirect('user/homework');
            }
        } else {
            parent::view([], 'login');
        }
    }
}
