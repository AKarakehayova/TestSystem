<?php

echo '<html>
    <head>
       <link rel="stylesheet" href="/resources/css/styles.css">
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
       <script>
           $(document).ready(function (){
                $(".testhomework").on("click", function(asd) {
                  var homeworkId = $(this).data("id");
                    $.ajax({
                      url: "/test/homework/" + homeworkId,
                      context: document.body
                    }).done(function() {
                      location.reload();
                    });
                })
           }) 
       </script>
    </head>
    
    <body>';

if (isset($_SESSION['user'])) {
    echo '<form action="/logout" method="POST">';
    echo '<button>Logout</button>';
    echo '</form>';

    if($_SESSION['user']['admin'] == 1) {
        echo '<nav>';
        echo '<ul>';
        echo '<li><a href="/admin/add-students">Manage Students</a></li>';
        echo '<li><a href="/admin/homework/upload">Upload New Homework</a></li>';
        echo '<li><a href="/admin/homework">Homework</a></li>';
        echo '<li><a href="/admin/settings">Settings</a></li>';
        echo '<li><a href="/admin/help">Help</a></li>';
        echo '</ul>';
        echo '</nav>';
    } else {
        echo '<nav>';
        echo '<ul>';
        echo '<li><a href="/user/homework">Homework</a></li>';
        echo '</ul>';
        echo '</nav>';
    }
}
