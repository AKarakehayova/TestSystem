<?php
namespace App\Repositories;
use App\Utils\DB;

class AdminRepository
{
    public static function getStudents() {
       return DB::query('SELECT `users`.`id`, `users`.first_name, `users`.last_name, `course_students`.faculty_number FROM `course_students` LEFT JOIN `users` ON course_students.faculty_number = users.faculty_number');
    }

    public static function addStudent($id) {
        return DB::query('INSERT INTO `course_students` (`faculty_number`) VALUES(' . $id . ')');
    }
}
