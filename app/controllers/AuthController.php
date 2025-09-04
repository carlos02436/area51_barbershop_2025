<?php
class AuthController {
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header('Location: /area51_barbershop_2025/index.php?page=login');
        exit();
    }
}