<?php

use App\Http\Router;

//add routes here
Router::set('POST', 'admin/homework/edit/{id}', 'AdminController', 'editHomework');
Router::set('GET', '/', 'HomeController', 'index');

Router::set('GET', 'home', 'HomeController', 'test');

Router::set('GET', 'login', 'LoginController', 'show');
Router::set('POST', 'login', 'LoginController', 'login');
Router::set('POST', 'logout', 'LoginController', 'logout');

Router::set('GET', 'register', 'RegisterController', 'show');
Router::set('POST', 'register', 'RegisterController', 'register');

//===============================admin===================================
Router::set('POST', 'admin/homework', 'AdminController', 'postHomework');

Router::set('GET', 'admin/homework', 'AdminController', 'listHomework');
Router::set('GET', 'admin/homework/{id}', 'AdminController', 'showHomework');
Router::set('GET', 'admin/homework/{id}/user/{id}', 'AdminController', 'showUserHomework');
Router::set('POST', 'admin/homework/{id}/user/{id}', 'AdminController', 'editUserHomework');
Router::set('GET', 'admin/homework/upload', 'AdminController', 'showUploadHomework');

Router::set('GET', 'admin/add-students', 'AdminController', 'showStudents');
Router::set('POST', 'admin/add-student', 'AdminController', 'addStudent');

Router::set('GET', 'admin/settings', 'AdminController', 'showSettings');
Router::set('POST', 'admin/settings', 'AdminController', 'postSettings');

Router::set('GET', 'admin/user/{id}', 'AdminController', 'showUser');
Router::set('POST', 'admin/user/{id}', 'AdminController', 'editUser');

Router::set('GET', 'admin/help', 'AdminController', 'showHelp');

//===============================user===================================
Router::set('GET', 'user/homework/{id}', 'UserController', 'showHomework');
Router::set('GET', 'user/homework', 'UserController', 'listHomework');
Router::set('POST', 'user/homework/{id}', 'UserController', 'postHomework');
//==============================tests==============================
Router::set('GET', 'test', 'TestController', 'testAll');
Router::set('GET', 'test/homework/{id}', 'TestController', 'testHomework');

//don't touch this
Router::route();

