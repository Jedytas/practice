<?php
    if (isset($_POST['opt'])) {
        setcookie('form_selected', $_POST['opt'], 0, '/');
    }
?>