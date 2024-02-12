<?php
/*** set the content type header ***/
/*** Without this header, it wont work ***/
header("Content-type: text/css");


# $php-variable = 'something';

/* <?=$php-variable?> */
?>
.registerForm, .loginForm {
    display: grid;
    justify-items: center;
    justify-content: center;
}
input {
    width: 250px;
}
input[type="checkbox"] {
    width: 1.3rem;
    height: 1.3rem;
}
.formRow {
    display: flex;
    align-items: center;
}
button[type="submit"] {
    margin: 1vh;
    height: 1.6rem;
    width: 200px;
}
