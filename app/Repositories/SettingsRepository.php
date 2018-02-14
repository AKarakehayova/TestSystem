<?php

namespace App\Repositories;

use App\Utils\DB;

class SettingsRepository
{
    public function getGradeFormula() {
        return DB::getFirst('SELECT * FROM `settings` WHERE `setting_name` = "grade_formula"');
    }
    
    public function modifyGradeFormula() {
        $query = 'UPDATE `settings` SET `setting_value` = "' . $_POST[key($_POST)] . '" WHERE `setting_name` = "' . key($_POST) . '"';

        DB::query($query);
    }
}