<?php
session_start();
session_unset();
session_destroy();
header('Location: /area51_barbershop_2025/index.php?page=login');
exit();