<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Http\Router;
class LoginController extends Controller
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
            parent::view([], 'login');
        }
    }

    public function login() {
        $result = $this->authRepository->login($_POST);
        if(!empty($result['data'][0])){
            unset($result['data'][0]['password']);
            $_SESSION['user'] = $result['data'][0];
            if($result['data'][0]['admin'] == 1) {
                Router::redirect('admin/homework');
            }
            Router::redirect('user/homework');
        }

        Router::redirect('login', ['error' => true, 'message' => 'Incorrect credentials.']);
    }

    public function logout() {
        session_destroy();
        Router::redirect('/login');
    }
}


