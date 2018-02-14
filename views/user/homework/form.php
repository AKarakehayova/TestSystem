<?php

if(!empty($_SESSION['redirect_params']['errors'])) {
    echo '<div class="error">';
    foreach ($content['errors']['errors'] as $error) {
        echo $error . '<br>';
    }
    echo '</div>';
}
    $homework = $content;
    $now = time(); // or your date as well
    $deadline = strtotime($homework['deadline']);
    $date_diff = $deadline - $now;
    $homework['submitted'] = false;

    $deadline_danger = '';

    $date_diff = round($date_diff / (60 * 60 * 24));
    if ($homework['submitted']) {
        $deadline_style = 'submitted';
    }
    if ($date_diff >= 7) {
        $deadline_style = 'information';
    } else if($date_diff >= 4) {
        $deadline_style = 'warning';
    } else {
        $deadline_style = 'danger';
    }


    echo'<form class="loginForm wide" action="/user/homework/' . $homework['id'] . '" method="POST" enctype="multipart/form-data">';
    echo'<div class="form-header">Upload Homework</div>';
    if(isset($_SESSION['redirect_params']['success']) && $_SESSION['redirect_params']['success']){
        echo '<div class="success">';
        echo 'Successful Upload';
        echo '</div>';
    }
    echo'<div class="form-header">' . $homework['name'] . '</div>';
    echo'<div class="homework-description">' . $homework['description'] . '</div>';
    if ($homework['submitted']) {
        echo'<div class="form-header submitted">Submitted</div>';
    } else {
        echo'<div class="form-header ' . $deadline_style . '">' . $homework['deadline'] . ' (' . $date_diff . ' days remaining)' .'</div>';
    }

    echo'<input type="file" placeholder="Upload file" name="homeworkFile" required>';

    $button_type = '';
    if ($date_diff < 0 || $homework['submitted']) {
        $button_type = 'submit';
    } else {
        $button_type = 'submit';
    }

    echo'<button type="' . $button_type . '">Upload</button>';
    echo'</form>';
