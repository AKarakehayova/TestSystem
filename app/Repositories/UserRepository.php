<?php

namespace App\Repositories;

use App\Utils\DB;

class UserRepository extends DB
{
    public function getUser($id)
    {
        return DB::getFirst('SELECT * FROM `users` WHERE `id`=' . $id);
    }

    public function getUserByFacultyNumber($fn)
    {
        return DB::getFirst('SELECT * FROM `users` WHERE `faculty_number`=' . $fn);
    }
}
