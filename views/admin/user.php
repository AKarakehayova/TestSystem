<?php


echo '<div class="centered-text">Name: ' . $content['user']['first_name'] . " " . $content['user']['last_name'] . '</div>';
echo '<div class="centered-text">Email: ' . $content['user']['email'] . '</div>';
echo '<div class="centered-text">Faculty number: ' . $content['user']['faculty_number'] . '</div>';
echo '<table class="centered-table wide">';
echo '<tr>';
echo '<th>Homework Name</th>';
echo '<th>Automatic Grade</th>';
echo '<th>Admin Grade</th>';
echo '<th>Final Grade</th>';
echo '<th>Edit Grade or View Homework</th>';
echo '</tr>';

foreach($content['homework'] as $homework){
    echo '<tr>';
    echo '<td><a href="/admin/homework/' . $homework['homework_id'] . '">' . $homework['name'] .'</a></td>';
    echo '<td>' . $homework['tests_rating'] .'</td>';
    echo '<td>' . $homework['admin_rating'] .'</td>';
    echo '<td>' . $homework['final_grade'] .'</td>';
    echo '<td><a href="/admin/homework/' . $homework['homework_id'] . '/user/' . $homework['student_id'] . '">Edit</a></td>';
    echo '</tr>';
}
echo '</table>';