<?php

echo '<form class="loginForm" action="/admin/settings' . '" method="POST">';
echo'<div class="form-header">' . $content['grade_formula']['setting_description'] . '</div>';
echo'<input type="text" value="' . $content['grade_formula']['setting_value'] . '" name="' . $content['grade_formula']['setting_name'] . '" required>';
echo'<button type="submit">Save</button>';
echo '</form>';