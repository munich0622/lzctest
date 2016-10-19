<?php

function is_login() {
    return isset($_SESSION['user']) && isset($_SESSION['user']['uid']) && $_SESSION['user']['uid'] > 0;
}

