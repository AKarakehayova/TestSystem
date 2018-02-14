<?php

$linkToFolder = 'tests/' . $content['homework']['hw_uid'] . '/' . $content['homework']['faculty_number'];

echo '<div class="centered-text"><a target="_blank" href="' . $linkToFolder . '/tests/_output' . '">Homework Test Output</a></div>';
echo '<div class="centered-text"><a target="_blank" href="' . $linkToFolder . '">Link to Homework</a></div>';
echo '<div class="centered-text"><a target="_blank" href="' . $linkToFolder . '/solution/' . $content['homework']['faculty_number'] . '-' . $content['homework']['name'] . '.zip">Download Code</a></div>';

if(!empty($content['errors']['errors'])) {
    echo '<div class="error">';
    foreach ($content['errors']['errors'] as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}


echo '<form class="loginForm" action="/admin/homework/' . $content['homework']['homework_id'] . '/user/' . $content['homework']['student_id'] . '" method="POST">';
if(isset($_SESSION['redirect_params']['success']) && $_SESSION['redirect_params']['success']){
    echo '<div class="success">';
    echo 'Successful Edit';
    echo '</div>';
}
echo '<div class="form-header" title="' . $content['grade_formula']['setting_description']. '">Current Formula: ' . $content['grade_formula']['setting_value']. '</div>';
echo'<label for="tests_rating">Automatic Grade</label>';
echo '<input type="text" name="tests_rating" size="5" value="' . $content['homework']['tests_rating'] . '" disabled></input>';
echo'<label title="Set grade to the homework, that will be combined with the grade from the automatic tests" for="tests_rating">Admin Grade</label>';
echo '<input type="text" name="admin_rating" size="5" value="' . $content['homework']['admin_rating'] . '"></input>';
echo'<label title="Grade calculated from the automatic grade and the admin grade given a formula. Can be edited." for="tests_rating">Final Grade</label>';
echo '<input type="text" name="final_grade" size="5" value="' . $content['homework']['final_grade'] . '"></input>';
echo '<button type="submit">Update</button>';
echo '</form>';