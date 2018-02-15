<?php

namespace App\Repositories;

use App\Utils\DB;
use ZipArchive;

class HomeworkRepository
{
    private $forbiddenFolders = [
        '.',
        '..',
        'tests'
    ];

    public function listHomework($admin = false) {
        if ($admin) {
            $homework = DB::query('SELECT * FROM `homeworks`');
        } else {
            $homework = DB::query('SELECT * FROM `homeworks` WHERE `start_date` <="' . date("Y-m-d H:i:s") . '"');
        }

        return $homework;
    }

    public function getHomework($id) {
        $homework = DB::getFirst('SELECT * FROM `homeworks` WHERE `id`=' . $id);
        return $homework;
    }

    public function getUserHomework($studentId, $homeworkId = false) {

        $query = 'SELECT * FROM `student_homework`
             INNER JOIN `homeworks`
             ON `homeworks`.`id` = `student_homework`.`homework_id`
             INNER JOIN `users`
             ON `student_homework`.`student_id` = `users`.`id`
             WHERE {appendQuery}`student_id`=' . $studentId;

        if($homeworkId === false) {
            $query = str_replace('{appendQuery}', '', $query);
            $homework = DB::get($query);
        } else {
            $query = str_replace('{appendQuery}', '`homework_id`=' . $homeworkId .' AND ', $query);
            $homework = DB::getFirst($query);
        }

        return $homework;
    }

    public function setHomeworkGrade($homeworkId, $userId, $grade) {
        DB::query('UPDATE `student_homework` 
        SET `tests_rating` = ' . $grade . ' 
        WHERE `student_id`=' . $userId . ' AND `homework_id`=' . $homeworkId);
    }

    public function uploadAdminHomework($data, $hwUid) {
        $homework = DB::query('INSERT INTO `homeworks` (`description`, `start_date`, `deadline`, `name`, `hw_uid`) 
            VALUES("' . $data['description'] . '","' . $data['start_date'] . '","' . $data['deadline'] . '","' . $data['name'] . '","' . $hwUid . '")');
        return $homework;
    }

    public function updateAdminHomework($data, $hwUid) {
        $homework = DB::query('UPDATE `homeworks` SET ' .
            '`description`="' . $data['description'] .
            '", `start_date`="' . $data['start_date'] .
            '", `deadline`="'. $data['deadline'] .
            '", `name`="' . $data['name'] .
            '", `hw_uid`="' . $hwUid . '" WHERE `hw_uid` = "' . $hwUid . '"');
        return $homework;
    }

    public function uploadUserHomework($id) {
        $alreadySubmitted = DB::getFirst('SELECT * FROM `student_homework` WHERE `homework_id` = ' . $id . ' AND `student_id` = ' .  $_SESSION['user']['id']);
        if(!empty($alreadySubmitted)) {
            return [];
        }

        DB::query('INSERT INTO `student_homework` (`homework_id`, `student_id`, `created_at`) 
                  VALUES(' . $id . ',' . $_SESSION['user']['id'] . ',"' . date("Y-m-d H:i:s") . '")');
    }

    public function getSubmittedHomework($homeworkId) {
        return DB::get('SELECT `student_homework`.`student_id`, `student_homework`.`tests_rating`, `student_homework`.`admin_rating`
 , `student_homework`.`final_grade`, `student_homework`.`created_at` as `uploaded_at`, `users`.`id` as `user_id`, `users`.`first_name`, `users`.`last_name`, `users`.`faculty_number`, `users`.`email`
                        FROM `student_homework` 
                        LEFT JOIN `users` 
                        ON `users`.`id` = `student_homework`.`student_id` 
                        WHERE `student_homework`.`homework_id`=' . $homeworkId
        );
    }

    public function validateUploadUserHomework($homeworkId) {
        $errors = [];

        $homework = DB::query('SELECT * FROM `homeworks` WHERE `id` = ' . $homeworkId);

        if(empty($homework['data'])) {
            $errors[] = 'Homework does not exist!';
            return $errors;
        }

        if (strtotime($homework['data'][0]['deadline'] . ' 00:00:00') < time()) {
            $errors[] ='You have missed the deadline ;(';
            return $errors;
        }

        if ($_FILES['homeworkFile']['type'] !== 'application/zip') {
            if ($_FILES['homeworkFile']['type'] !== 'application/octet-stream') {
                $errors[] = 'Your homework must be zipped!';
            }
        }

        return $errors;

    }

    public function validateUploadAdminHomework($data, $update = false) {
        $errors = [];

        if(empty($data['name']) || empty($data['description']) || empty($data['start_date']) || empty($data['deadline']) || empty($_FILES)) {
            $errors[] = 'All fields are required!';
            return $errors;
        }

        if (strtotime($data['start_date']) >= strtotime($data['deadline'])) {
            $errors[] = 'The start date can not be after the deadline!';
        }

       if ($_FILES['homeworkFile']['type'] !== 'application/zip' && !$update) {
            if ($_FILES['homeworkFile']['type'] !== 'application/octet-stream') {
                $errors[] = 'The test files must be zipped!';
            }
        }

        return $errors;
    }

    public function uploadAdminHomeworkZip($folderId) {
        $error = '';

        if ($_FILES['homeworkFile']['size'] !== 0) {
          if ($_FILES['homeworkFile']['type'] === 'application/zip' || $_FILES['homeworkFile']['type'] === 'application/octet-stream') {
                $homeworkFolder = realpath(PATH_TO_TESTS_FOLDER . $folderId);

                move_uploaded_file($_FILES['homeworkFile']['tmp_name'], $homeworkFolder .'/tests/upload.zip');

                $studentHomework = scandir($homeworkFolder);
                $zip = new ZipArchive;
                foreach ($studentHomework as $folder) {
                    if (!in_array($folder, $this->forbiddenFolders)) {
                        $res = $zip->open($homeworkFolder .'/tests/upload.zip');
                        if ($res === true) {
                            $zip->extractTo($homeworkFolder . '/' . $folder . '/tests');
                            $zip->close();
                        }
                    }
                }

            } else {
                $error = 'The test files must be zipped!';
            }
        }

        return $error;
    }

    public function validateEditUserHomework($data, $homework) {
        $errors = [];
        $now = date('Y-m-d H:i:s');
        $homeworkDeadline = $homework['deadline'] . " 00:00:00";

        if ($now <= $homeworkDeadline) {
            $errors[] = 'You cant set grades for homework before its deadline';
            return $errors;
        }

        if(empty($data['admin_rating'])) {
            $errors[] = 'You must enter Admin Grade';
        }


        if (!empty($data['admin_rating']) && ($data['admin_rating'] < 2 || $data['admin_rating'] > 6)) {
            $errors[] = 'The Admin Grade must be a number between 2 and 6';
        }

        if (!empty($data['final_grade']) && ($data['final_grade'] < 2 || $data['final_grade'] > 6)) {
            $errors[] = 'The Final Grade must be a number between 2 and 6';
        }

        return $errors;
    }

    public function editUserHomeworkAdminRating($homework, $studentId, $gradeFormula) {

        if (empty($_POST['final_grade'])) {
            $gradeFormula = str_replace('x', $homework['tests_rating'], $gradeFormula);
            $gradeFormula = str_replace('y', $_POST['admin_rating'], $gradeFormula);

            eval('$_POST[\'final_grade\'] ='. $gradeFormula .';');
        }

        $query = 'UPDATE `student_homework`
        SET `admin_rating` = ' . $_POST['admin_rating'] .
            ', `final_grade` = ' . $_POST['final_grade'] .
        ' WHERE `homework_id` = ' . $homework['homework_id'] . ' AND `student_id` = ' . $studentId;

        DB::query($query);
    }

    public function markTested($homeworkId) {
        DB::query('UPDATE `homeworks` SET `tested` = 1 WHERE `id` = ' . $homeworkId);
    }

    public function getUntestedHomework() {
        return DB::get('SELECT * FROM `homeworks` WHERE `tested` = 0 AND `deadline` = "' . date("Y-m-d") . '"');
    }

    public function userSubmittedHomework($userId, $homeworks)
    {
        foreach($homeworks['data'] as &$homework) {
            $homework['student_homework'] = $this->getStudentHomework($userId, $homework['id']);
        }

        return $homeworks;
    }

    public function getStudentHomework($userId, $homeworkId) {
        return DB::getFirst('SELECT * FROM `student_homework` WHERE `homework_id` = ' . $homeworkId . ' AND  `student_id` = ' . $userId);
    }
}
