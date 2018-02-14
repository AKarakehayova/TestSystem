<?php

    echo '<div class="error">';
    if(!empty($content['error']) && $content['error']) {
        foreach ($content['message'] as $message) {
            echo $message . '<br>';
        }
    }
    echo '</div>';

    echo '<div class="loginForm">';
    echo "<form action='/register' method='POST'>";
    echo'<div class="form-header">Register</div>';
    echo '<input type="text" placeholder="Faculty number" name="faculty_number" >';
    echo '<input type="text" placeholder="Username" name="username" >';
    echo '<input type="text" placeholder="First Name" name="first_name" >';
    echo '<input type="text" placeholder="Last Name" name="last_name" >';
    echo '<input type="text" placeholder="Email" name="email" >';
    echo '<input type="password" placeholder="Password" name="password" >';
    echo '<input type="password" placeholder="Confirm Password" name="password_confirm" >';
    echo '<button type="submit">Register</button>';
    echo '<a href="/login"><button type="button">Already Registered</button></a>';
    echo '</form>';
    echo '</div>';
