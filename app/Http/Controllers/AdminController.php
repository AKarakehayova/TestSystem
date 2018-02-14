<?php
namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Http\Router;
use App\Repositories\HomeworkRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;

class AdminController extends Controller
{
    private $settingsRepository;
    private $homeworkRepository;
    private $adminRepository;
    private $userRepository;

    const DUPLICATE_ENTRY_MESSAGE = 'Such Faculty number already exists';
    const DUPLICATE_ENTRY_CODE = 1062;

    public function __construct()
    {
        parent::__construct();
        parent::checkAdmin();
        $this->homeworkRepository = new HomeworkRepository();
        $this->settingsRepository = new SettingsRepository();
        $this->userRepository = new UserRepository();
        $this->adminRepository = new AdminRepository();
    }

    public function showStudents(){
        $students = $this->adminRepository->getStudents();
        parent::view(['students' => $students['data']], 'admin.students');
    }

    public function addStudent(){
        if (isset($_POST['faculty_number']) && !empty($_POST['faculty_number'])) {
            $result = $this->adminRepository->addStudent($_POST['faculty_number']);

            if ($result['error']) {
                switch($result['data']) {
                    case self::DUPLICATE_ENTRY_CODE:
                        Router::redirect('/admin/add-students', ['error' => true, 'message' => self::DUPLICATE_ENTRY_MESSAGE]);
                        break;
                }
            }
        }

        Router::redirect('/admin/add-students');
    }

    public function showSettings() {
        $gradeFormula = $this->settingsRepository->getGradeFormula();
        
         parent::view(['grade_formula' => $gradeFormula], 'admin.settings');
    }

    public function postSettings() {
        $this->settingsRepository->modifyGradeFormula();

        Router::redirect('/admin/settings', ['success' => true]);
    }

    public function showUploadHomework()
    {
        parent::view([], 'admin.homework.upload');
    }

    public function listHomework()
    {
        $homework = $this->homeworkRepository->listHomework(true);
        parent::view(['homework' => $homework], 'admin.homework.list');
    }

    public function postHomework()
    {
        $errors = $this->homeworkRepository->validateUploadAdminHomework($_POST);

        if (empty($errors)) {
            $folderId = uniqid();
            $testFolderPath = realpath(PATH_TO_TESTS_FOLDER);

            $homeworkFolder = $testFolderPath . '/' . $folderId;
            mkdir($homeworkFolder, '0744');
            mkdir($homeworkFolder . '/tests', '0744');
            move_uploaded_file($_FILES['homeworkFile']['tmp_name'], $homeworkFolder .'/tests/upload.zip');
            $this->homeworkRepository->uploadAdminHomework($_POST, $folderId);
            Router::redirect('/admin/homework', ['success' => true]);
        } else {
            Router::redirect('/admin/homework', ['errors' => $errors]);
        }
    }

    public function showHomework($id)
    {
        $students = $this->homeworkRepository->getSubmittedHomework($id);
        $homework = $this->homeworkRepository->getHomework($id);

        if (!empty($homework)) {
            parent::view(['homework' => $homework, 'students' => $students], 'admin.homework.edit');
        } else {
            Router::redirect('/admin/homework');
        }
    }

    public function editHomework($id) {
        $errors = $this->homeworkRepository->validateUploadAdminHomework($_POST, true);

        $homework = $this->homeworkRepository->getHomework($id);
        $result = $this->homeworkRepository->uploadAdminHomeworkZip($homework['hw_uid']);

        if (!empty($result)) {
            $errors[] = $result;
        }

        if (empty($errors)) {
            $this->homeworkRepository->updateAdminHomework($_POST, $homework['hw_uid']);
            Router::redirect('/admin/homework/' . $homework['id'], ['success' => true]);
        } else {
            Router::redirect('/admin/homework/' . $homework['id'], ['errors' => $errors]);
        }
    }

    public function editUserHomework($homeworkId, $userId) {
        $homework = $this->homeworkRepository->getUserHomework($userId, $homeworkId);
        $gradeFormula = $this->settingsRepository->getGradeFormula();

        $errors = $this->homeworkRepository->validateEditUserHomework($_POST, $homework);

        if (empty($errors)) {
            $this->homeworkRepository->editUserHomeworkAdminRating($homework, $userId, $gradeFormula['setting_value']);
            Router::redirect('/admin/homework/' . $homeworkId . '/user/' . $userId , ['success' => true]);
        } else {
            Router::redirect('/admin/homework/' . $homeworkId . '/user/' . $userId , ['errors' => $errors]);
        }
    }

    public function showUserHomework($homeworkId, $userId) {
        $homework = $this->homeworkRepository->getUserHomework($userId, $homeworkId);
        $gradeFormula = $this->settingsRepository->getGradeFormula();
        
        parent::view(['homework' => $homework, 'grade_formula' => $gradeFormula], 'admin.homework.user');
    }

    public function showHelp() {
        parent::view([], 'admin.help');
    }

    public function showUser($userId) {
        $homework = $this->homeworkRepository->getUserHomework($userId);
        $user = $this->userRepository->getUser($userId);

        parent::view(['homework' => $homework, 'user' => $user], 'admin.user');
    }
}
