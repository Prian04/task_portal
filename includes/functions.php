<?php
function generatePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    return substr(str_shuffle($chars), 0, $length);
}

function validatePassword($password) {
    return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
