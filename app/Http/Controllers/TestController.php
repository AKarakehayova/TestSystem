<?php

namespace App\Http\Controllers;

use App\Repositories\HomeworkRepository;
use App\Repositories\UserRepository;

class TestController
{
    const TEST_COMMAND = 'cd {$path} && sudo php codecept.phar run | tail -n 2';
    const CHMOD_COMMAND = 'sudo chmod -R 777 {$dirpath}';

    private $forbiddenFolders = [
        '.',
        '..',
        'tests'
    ];

    private $homeworkRepository;
    private $userRepository;

    public function __construct()
    {
        $this->homeworkRepository = new HomeworkRepository();
        $this->userRepository = new UserRepository();
    }

    public function testHomework($homeworkId)
    {
        $checkChromedriverCommand = 'curl -I http://127.0.0.1:9515/wd/hub';
        $startChromeDriverCommand = 'chromedriver --url-base=/wd/hub 1>/dev/null &';

        exec($checkChromedriverCommand, $output, $code);
        if ($code === 7) {
            exec($startChromeDriverCommand);
        }

        $homework = $this->homeworkRepository->getHomework($homeworkId);
        $this->homeworkRepository->getUntestedHomework();
        $homeworkFolder = PATH_TO_TESTS_FOLDER . $homework['hw_uid'];

        $command =  str_replace('{$dirpath}', $homeworkFolder, self::CHMOD_COMMAND);
        shell_exec($command);

        $studentHomework = scandir($homeworkFolder);
        foreach ($studentHomework as $folder) {
            if (!in_array($folder, $this->forbiddenFolders)) {
                $student = $this->userRepository->getUserByFacultyNumber($folder);
                $grade = $this->testUserHomework($homework['hw_uid'], $student);
                $this->homeworkRepository->setHomeworkGrade($homeworkId, $student['id'], $grade);
            }
        }

        $this->homeworkRepository->markTested($homeworkId);
    }

    public function testUserHomework($homeworkUid, $student)
    {
        $homeworkFolder =  PATH_TO_TESTS_FOLDER . $homeworkUid . '/' . $student['faculty_number'];
        $result = shell_exec(str_replace('{$path}', $homeworkFolder, self::TEST_COMMAND));

        $score = $this->homeworkResultParser($result);

        return $score;
    }

    public function homeworkResultParser($result)
    {
        if (strpos($result, 'OK') === false) {
                    $regex = '/([a-zA-Z]+):\s([0-9]+)/';
                    $matches = preg_match_all($regex, $result, $out);
            if ($matches) {
                $numberOfAssertions = $out[2][1];
                $numberOfFailures = $out[2][2];

                if (intval($numberOfAssertions) === 0 ) {
                    return 2;
                }
                $grade = 2 + 4/$numberOfAssertions * ($numberOfAssertions - $numberOfFailures);
                return round($grade, 2);
            }
        }
        return 6;
    }
}
