<?php
session_start();

// Verifico se l'utente è loggato
if (!isset($_SESSION["username"])) {
    // Se l'utente non è loggato, redirect alla login
    header("Location: login.php");
    exit();
}

// Verifico se è stato fornito un ID del post da eliminare
if (!isset($_GET["id"])) {
    // Se l'ID del post non è stato fornito, redirect alla dashboard
    header("Location: dashboard.php");
    exit();
}

//ID del post dalla query
$post_id = $_GET["id"];

// Connessione al database
$conn = new mysqli("localhost", "root", "root", "php-blog");

// Controllo la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

//Query per eliminare il post
$sql = "DELETE FROM posts WHERE id = $post_id";

if ($conn->query($sql) === TRUE) {
    $_SESSION["success_message"] = "Il post è stato eliminato con successo.";
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION["error_message"] = "Si è verificato un errore durante l'eliminazione del post.";
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>