<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// Eliminar usuario
$conn->query("DELETE FROM usuarios WHERE id = $id");

header("Location: admin-usuarios.php");
exit();
