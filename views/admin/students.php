<?php
echo '<table class="centered-table">';
echo '<tr>';
echo '<th>Faculty Number</th>';
echo '<th>Name</th>';
echo '</tr>';

foreach($content['students'] as $student){
    echo '<tr>';
    if (!empty($student['first_name']) && !empty($student['last_name'])) {
        $studentName = $student['first_name'] . " " . $student['last_name'];
    } else {
        $studentName = 'Not Registered yet';
    }
    echo '<td>' . $student['faculty_number'] . '</td>';
    echo '<td><a href="/admin/user/' . $student['id'] . '">' . $studentName . '</a></td>';
    echo '</tr>';
}
echo '</table>';
if(!empty($content['errors']) && $content['errors']['error']){
    echo $content['errors']['message'];
}
echo '<form class="loginForm" action="/admin/add-student" method="POST">';
echo'<div class="form-header">Add Student</div>';
echo '<input type="text" name="faculty_number" placeholder="Faculty number" required/>';
echo '<button type="submit"> Add </button>';
echo '</form>';
