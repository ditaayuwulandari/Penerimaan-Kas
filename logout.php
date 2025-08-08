<?php
session_start();
session_unset();     // Hapus semua isi session
session_destroy();   // Akhiri session
header("Location: login.php"); // Kembali ke login
exit();
