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
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenuto:</label>
                <textarea id="content" name="content" class="form-control" rows="5" required></textarea>
            </div>
            <select id="category" class="form-select mb-3 w-25" aria-label="Seleziona la categoria" name="category"
                required>
                <option value="" disabled selected>Scegli la categoria</option>
                <?php
                // Connessione al database
                $conn = new mysqli("127.0.0.1", "root", "root", "php-blog");

                // Controllo la connessione
                if ($conn->connect_error) {
                    die("Connessione al database fallita: " . $conn->connect_error);
                }

                // Eseguo la query per ottenere le categorie
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);

                // Output delle opzioni per la select
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
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
    $category_id = $_POST["category"];
    // Altri campi del form...

    // Eseguo l'inserimento dei dati nel database
    $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id, category_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $content, $user_id, $category_id); // Modifica dei parametri della bind_param per includere anche la categoria

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