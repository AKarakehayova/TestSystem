<?php

    if(!empty($content['errors']) && $content['errors']['error']){
        echo '<div class="error">';
        echo $content['errors']['message'] . '<br>';
        echo '</div>';
    }
    
    echo'<form class="loginForm" action="/login" method="POST">';
    echo'<div class="form-header">Login</div>';
    if(isset($_SESSION['redirect_params']['error']) && !$_SESSION['redirect_params']['error']){
        echo '<div class="success">';
        echo 'Successful Registration';
        echo '</div>';
    }
    echo'<input type="text" placeholder="Enter Username" name="username" required>';
    echo'<input type="password" placeholder="Enter Password" name="password" required>';

    echo'<button type="submit">Login</button>';
    echo '<a href="/register"><button type="button">Register</button></a>';
    echo '</form>';
