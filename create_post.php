<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Nuovo Post</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- header -->
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2>Crea Nuovo Post</h2>
        <form action="create_post.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titolo:</label>
                <input type="text" id="title" name="title" class="form-control" placeholder='Soy un malessere'>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenuto:</label>
                <textarea placeholder='El mejor malessere de la ciudad' id="content" name="content" class="form-control"
                    rows="5"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Crea Post</button>
        </form>
    </div>

    <!-- Script di Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<?php

// Verifico se l'utente è loggato
if (!isset($_SESSION["username"])) {
    // Se l'utente non è loggato, redirect alla login
    header("Location: login.php");
    exit();
}

// Verifico se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Raccolta dei dati dal form
    $title = $_POST["title"];
    $content = $_POST["content"];
    $user_id = $_SESSION["user_id"];
    // Altri campi del form...

    // Connessione al database
    $conn = new mysqli("127.0.0.1", "root", "root", "php-blog");

    // Controllo la connessione
    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    // Eseguo l'inserimento dei dati nel database
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $user_id);

    if ($stmt->execute()) {
        // Inserimento riuscito, redirect alla dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Errore durante l'inserimento showo un messaggio di errore
        echo "Errore durante l'inserimento del post nel database.";
    }

    $stmt->close();
    $conn->close();
}
?>