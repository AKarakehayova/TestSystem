<?php

namespace App\Cron;

use App\Repositories\HomeworkRepository;
use App\Http\Controllers\TestController;

require __DIR__ . '/../../bootstrap/autoload.php';
require __DIR__ . '/../../config/config.php';


$homeworkRepository = new HomeworkRepository();
$testController = new TestController();

$homeworks = $homeworkRepository->getUntestedHomework();
if(!empty($homeworks)) {
    foreach ($homeworks as $homework) {
        $testController->testHomework($homework['id']);
    }
}
