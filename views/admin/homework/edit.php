<?php

if(!empty($content['errors']['errors'])) {
    echo '<div class="error">';
    foreach ($content['errors']['errors'] as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}
echo'<form class="loginForm" action="/admin/homework/edit/' . $content['homework']['id']. '" method="POST" enctype="multipart/form-data">';

if(isset($_SESSION['redirect_params']['success']) && $_SESSION['redirect_params']['success']){
    echo '<div class="success">';
    echo 'Successful Edit';
    echo '</div>';
}

echo'<div class="form-header">Edit Homework</div>';
echo'<input type="text" placeholder="Homework name" name="name" value="' . $content['homework']['name']. '" >';
echo'<textarea placeholder="Homework Description" name="description">' . $content['homework']['description']. '</textarea>';
echo'<label for="start_date">Start Date</label>';
echo'<input type="date" placeholder="Start Date" name="start_date" value="' . $content['homework']['start_date']. '">';
echo'<label for="deadline">Deadline</label>';
echo'<input type="date" placeholder="Deadline" name="deadline" value="' . $content['homework']['deadline']. '">';
echo '<a href="/tests/' . $content['homework']['hw_uid'] . '/tests/upload.zip">Download Uploaded Tests</a><br>';
echo'<label for="homeworkFile">Upload New Tests</label>';
echo'<input type="file" name="homeworkFile" >';
echo'<button type="submit">Edit</button>';
echo '<button type="button" class="testhomework" data-id="' . $content['homework']['id'] . '">Test</button>';
echo'</form>';


echo '<table class="centered-table wide">';
echo '<tr>';
echo '<th>User ID</th>';
echo '<th>Name</th>';
echo '<th>Email</th>';
echo '<th>Faculty Number</th>';
echo '<th>Automatic Grade</th>';
echo '<th>Admin Grade</th>';
echo '<th>Final Grade</th>';
echo '<th>Uploaded at</th>';
echo '</tr>';

foreach($content['students'] as $student){
    echo '<tr>';
    
    echo '<td>' .
        '<a title="View the user" href="/admin/user/' . $student['user_id'] . '">' .
        $student['user_id'] . '</td>';
    echo '<td>'. 
    '<a title="View the specific homework of the user" href="/admin/homework/' . $content['homework']['id'] . '/user/' . $student['user_id'] . '">'
    . $student['first_name'] . $student['last_name'] . '</a></td>';
    echo '<td>' . $student['email'] . '</td>';
    echo '<td>' . $student['faculty_number'] . '</td>';
    echo '<td>' . $student['tests_rating'] . '</td>';
    echo '<td>' . $student['admin_rating'] . '</td>';
    echo '<td>' . $student['final_grade'] . '</td>';
    echo '<td>' . $student['uploaded_at'] . '</td>';
    echo '</a></tr>';
}
echo '</table>';
//

