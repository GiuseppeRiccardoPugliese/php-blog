<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php
    include 'header.php';
    ?>

    <!-- FORM LOGIN -->
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" value="Login">Login</button>
    </form>

</body>

</html>


<?php

// Se l'utente è loggato, redirect alla login
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connessione al database
    $conn = new mysqli("127.0.0.1", "root", "root", "php-blog");

    // Controllo la connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Eseguo la query utilizzando prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // L'utente è stato trovato, verifico la password password_verify()
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            // Password corretta, salvo l'utente in sessione e redirect alla dashboard
            $_SESSION["username"] = $user["username"];
            $_SESSION['user_id'] = $user['id'];
            // Messaggio di benvenuto
            echo "Benvenuto, " . $user["username"] . "! Accesso effettuato con successo.";
            // Reindirizzamento alla dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password sbagliata, mostro un messaggio di errore
            echo "Username o password non validi.";
        }
    } else {
        // Utente non trovato, mostro un messaggio di errore
        echo "Utente non trovato.";
    }

    $stmt->close();
    $conn->close();
}
?>