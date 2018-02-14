<?php

    if(!empty($content['errors']['errors'])) {
        echo '<div class="error">';
        foreach ($content['errors']['errors'] as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
    echo'<form class="loginForm" action="/admin/homework" method="POST" enctype="multipart/form-data">';
    if(isset($_SESSION['redirect_params']['success']) && $_SESSION['redirect_params']['success']){
        echo '<div class="success">';
        echo 'Successful Upload';
        echo '</div>';
    }
    echo'<div class="form-header">Upload Homework</div>';
    echo'<input type="text" placeholder="Homework name" name="name">';
    echo'<textarea placeholder="Homework Description" name="description"></textarea>';
    echo'<label for="start_date">Start Date</label>';
    echo'<input type="date" placeholder="Start Date" name="start_date">';
    echo'<label for="deadline">Deadline</label>';
    echo'<input type="date" placeholder="Deadline" name="deadline">';
    echo'<input type="file" name="homeworkFile" >';
    echo'<button type="submit">Upload</button>';
    echo'</form>';
