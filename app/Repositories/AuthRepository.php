<?php

namespace App\Repositories;

use App\Utils\DB;

class AuthRepository
{
    const MIN_PASSWORD_LENGTH = 8;

    public function login($data){
        $hashedPassword = crypt($data['password'], PASSWORD_HASH);

        return DB::query('SELECT * FROM `users` WHERE `username`= "' .$data['username'] . '" AND `password`= "' . $hashedPassword . '"');
    }

    public function validate($data){
        $errors = [];
        if(empty($data['username']) || empty($data['password']) || empty($data['password_confirm']) || empty($data['email']) || empty($data['first_name']) || empty($data['last_name']) || empty($data['faculty_number'])) {
            $errors[] = 'All fields are required!';
            return $errors;
        }

        if($data['password'] !== $data['password_confirm']){
            $errors[] = 'Passwords do not match!';
        }

        if(strlen($data['password']) < self::MIN_PASSWORD_LENGTH){
            $errors[] = 'Password must be at least ' . self::MIN_PASSWORD_LENGTH .' symbols!';
        }

        $result = DB::query('SELECT * FROM `course_students` WHERE `faculty_number` = ' . $data['faculty_number']);
        if(empty($result['data'])){
            $errors[] = 'Your faculty number is not right. Try again or talk to your professor.';
        }

        $result = DB::query('SELECT * FROM `users` WHERE `username` = "' . $data['username'] . '"');
        if(!empty($result['data'])){
            $errors[] = 'This username is already taken.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'This email is not valid.';
        }

        return $errors;
    }

    public function register($data) {
        $errors = $this->validate($data);
        $hashedPassword = crypt($data['password'], PASSWORD_HASH);

        if(empty($errors)){
            $checkExistingUser = DB::getFirst('SELECT * FROM `users` WHERE `faculty_number` = ' . $data['faculty_number']);
            if (!empty($checkExistingUser)) {
                return ['error' => true, 'message' => ['This faculty number is already registered']];
            }

             return DB::query('INSERT INTO users (`username`, `password`, `email`, `first_name`, `last_name`, `faculty_number`, `admin`, `created_at`)
          VALUES("' . $data['username'] . '","'  . $hashedPassword . '","'  . $data['email'] . '","'  . $data['first_name'] . '","'  . $data['last_name'] . '","' . $data['faculty_number'] . '", 0, "' . date("Y-m-d H:i:s") . '")');
        }

        return ['error' => true, 'message' => $errors];
    }
}
