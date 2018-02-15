<?php

echo '<table class="centered-table wide">';
echo '<tr>';
echo '<th>Homework</th>';
echo '<th>Date Published</th>';
echo '<th>Deadline</th>';
echo '<th>Days Remaining</th>';
echo '<th>Submitted</th>';
echo '<th>Graded</th>';
echo '</tr>';

foreach($content['homework']['data'] as $homework){
    $now = time(); // or your date as well
    $deadline = strtotime($homework['deadline']);
    $date_diff = $deadline - $now;

    $deadline_danger = '';

    $date_diff = round($date_diff / (60 * 60 * 24));
    if (!empty($homework['student_homework'])) {
        $deadline_style = 'submitted';
    }
    if ($date_diff >= 7) {
        $deadline_style = 'information';
    } else if($date_diff >= 4) {
        $deadline_style = 'warning';
    } else {
        $deadline_style = 'danger';
    }

    echo '<tr class="' . $deadline_style . '">';

    echo '<td>' . '<a href="/user/homework/' . $homework['id'] . '">' . $homework['name'] . '</a>' . '</td>';
    echo '<td>' . $homework['start_date'] . '</td>';
    echo '<td>' . $homework['deadline'] . '</td>';
    echo '<td>' . $date_diff . '</td>';
    echo '<td>' . (!empty($homework['student_homework']) ? 'Yes' : 'No') . '</td>';
    if (!empty($homework['student_homework'])) {
        echo '<td>' . (!empty($homework['student_homework']['final_grade']) ? $homework['student_homework']['final_grade'] : 'No') . '</td>';
    } else {
        echo '<td>No</td>';
    }
    echo '</tr>';
}
echo '</table>';


