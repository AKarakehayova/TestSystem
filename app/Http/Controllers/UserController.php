<?php

namespace App\Http\Controllers;

use App\Http\Router;
use App\Repositories\HomeworkRepository;
use App\Repositories\UserRepository;
use ZipArchive;

class UserController extends Controller
{
    private $homeworkRepository;
    private $userRepository;

    private $forbiddenFiles = [
        'codecept.phar',
        'codeception.yml',
        'tests'
    ];

    private $systemDirs = [
        '.',
        '..'
    ];

    public function __construct()
    {
        parent::__construct();
        parent::checkUser();
        $this->homeworkRepository = new HomeworkRepository();
        $this->userRepository = new UserRepository();
    }

    public function showHomework($id)
    {
        $homework = $this->homeworkRepository->getHomework($id);
        if (!empty($homework)) {
            parent::view($homework, 'user.homework.form');
        } else {
            Router::redirect('/user/homework');
        }
    }

    /**
     * @param $id
     */
    public function postHomework($id)
    {
        $homework = $this->homeworkRepository->getHomework($id);
        $testFolderPath = realpath(PATH_TO_TESTS_FOLDER . $homework['hw_uid']);
        $user = $this->userRepository->getUser($_SESSION['user']['id']);
        $studentSolutionFolder = $testFolderPath . '/' . $user['faculty_number'] .'/solution';


        if (empty($_FILES)) {
            Router::redirect('/user/homework/' . $id, ['errors' => ['No file was uploaded, try again and check its size']]);
        }

        if($_FILES['homeworkFile']['size'] > 10000000) {
            Router::redirect('/user/homework/' . $id, ['errors' => ['Archive too big']]);
        }
        //put in separate method prolly in homeworkuploadrepo
        $zip = new ZipArchive;

        //extracting uploaded files
        $res = $zip->open($_FILES['homeworkFile']['tmp_name']);
        if ($res === true) {

            for( $i = 0; $i < $zip->numFiles; $i++ ){
                $stat = $zip->statIndex( $i );
                $fileName = basename( $stat['name'] );
                if(in_array($fileName, $this->forbiddenFiles)) {
                    Router::redirect('/user/homework/' . $id, ['errors' => ['Forbidden files in archive']]);
                }
            }

            $zip->extractTo($testFolderPath . '/' . $user['faculty_number']);
            $zip->close();
        }

        //extracting lib
        $res = $zip->open(ROOT_DIR . '/lib/tests.zip');
        if ($res === true) {
            $zip->extractTo($testFolderPath . '/' . $user['faculty_number']);
            $zip->close();
        }

        //extracting admin tests
        $res = $zip->open($testFolderPath . '/tests/upload.zip');
        if ($res === true) {
            $zip->extractTo($testFolderPath . '/' . $user['faculty_number'] . '/tests');
            $zip->close();
        }

        $acceptanceConfigPath = $testFolderPath . '/' . $user['faculty_number'] . '/tests/acceptance.suite.yml';
        $acceptanceConfig = file_get_contents($acceptanceConfigPath);
        $acceptanceConfig = str_replace('{$url}', SITE_URL, $acceptanceConfig);
        $acceptanceConfig = str_replace('{$hwId}', $homework['hw_uid'], $acceptanceConfig);
        $acceptanceConfig = str_replace('{$student}', $user['faculty_number'], $acceptanceConfig);

        $myfile = fopen($testFolderPath . '/' . $user['faculty_number'] . '/tests/acceptance.suite.yml', "w");
        fwrite($myfile, $acceptanceConfig);
        fclose($myfile);

        //replace namespace in unit tests
        $unitTestDir = $testFolderPath . '/' . $user['faculty_number'] . '/tests/unit';
        $unitTests= scandir($unitTestDir);
        foreach ($unitTests as $unitTest) {
            if (!in_array($unitTest, $this->systemDirs)) {
                $unitTestFile = file_get_contents($unitTestDir . '/' . $unitTest);
                $unitTestFile = str_replace('{$hwId}', $homework['hw_uid'], $unitTestFile);
                $unitTestFile = str_replace('{$student}', $user['faculty_number'], $unitTestFile);

                $myfile = fopen($unitTestDir . '/' . $unitTest, "w");
                fwrite($myfile, $unitTestFile);
                fclose($myfile);
            }

        }

        if(!file_exists ($studentSolutionFolder)) {
            mkdir($studentSolutionFolder, '0744');
        }
        move_uploaded_file($_FILES['homeworkFile']['tmp_name'], $studentSolutionFolder . '/' . $user['faculty_number'] . '-' . $homework['name'] .'.zip');

        $errors = $this->homeworkRepository->validateUploadUserHomework($id);

        if (empty($errors)) {
            $this->homeworkRepository->uploadUserHomework($id);
            Router::redirect('/user/homework/' . $id, ['success' => true]);
        } else {
            Router::redirect('/user/homework/' . $id, ['errors' => $errors]);
        }
    }

    public function listHomework()
    {
        $homework = $this->homeworkRepository->listHomework();

        $homework = $this->homeworkRepository->userSubmittedHomework($_SESSION['user']['id'], $homework);
        parent::view(['homework' => $homework], 'user.homework.list');
    }

}
