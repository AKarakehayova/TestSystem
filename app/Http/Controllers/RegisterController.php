<?php

namespace App\Http\Controllers;

use App\Http\Router;
use App\Repositories\AuthRepository;

class RegisterController extends Controller
{
    private $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    public function show() {
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['admin'] == 1) {
                Router::redirect('admin/homework');
            } else {
                Router::redirect('user/homework');
            }
        } else {
            parent::view([], 'register');
        }
    }

    public function register() {
        $status = $this->authRepository->register($_POST);

        if ($status['error']) {
            parent::view($status, 'register');
        } else {
            Router::redirect('/login', $status);
        }
    }
}
