<?php
session_start();

// Verifico se l'utente è loggato
if (!isset($_SESSION["username"])) {
    // Se l'utente non è loggato, redirect alla login
    header("Location: login.php");
    exit();
}

// Connessione al database
$conn = new mysqli("localhost", "root", "root", "php-blog");

// Controllo la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Verifica se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["id"])) {
    // ID del post dalla query string
    $post_id = $_GET["id"];

    // Raccolta dei dati dal form
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Query per aggiornare il post nel database
    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $post_id);

    if ($stmt->execute()) {
        // Aggiornamento riuscito, esegui l'upload dell'immagine se è stata fornita
        if (isset($_FILES["newImage"]) && $_FILES["newImage"]["error"] == UPLOAD_ERR_OK) {
            // Visualizza il nome del file newImage
            echo $_FILES["newImage"]["name"];

            include 'upload_image.php'; // Includi il file di upload dell'immagine
            exit(); // Termina lo script dopo l'upload dell'immagine
        } else {
            // Se non è stata fornita alcuna immagine, reindirizza alla dashboard o mostra un messaggio di successo
            header("Location: dashboard.php");
            exit();
        }
    } else {
        // Errore durante l'aggiornamento, mostra un messaggio di errore
        echo "Errore durante l'aggiornamento del post nel database.";
    }


    $stmt->close();
}

// Se l'ID del post non è specificato nella query string o se il post non esiste nel database, reindirizza alla dashboard
if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = $_GET["id"];

// Query per selezionare il post dall'ID specificato
$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = $conn->query($sql);

// Verifica se il post esiste
if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    // Se il post non esiste, reindirizza alla dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Post</title>
</head>

<?php
include 'header.php';
?>

<body>
    <div class="container mt-5">
        <h1>Modifica Post</h1>

        <form action="update_post.php?id=<?php echo $post_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titolo:</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo $post['title']; ?>">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenuto:</label>
                <textarea id="content" name="content" class="form-control"
                    rows="5"><?php echo $post['content']; ?></textarea>
            </div>

            <select class="form-select mb-3 w-25" aria-label="Seleziona la categoria" name="category">
                <option>Scegli la categoria</option>
                <?php
                // Connessione al database
                $conn = new mysqli("localhost", "root", "root", "php-blog");

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
                        if ($post['category_id'] == $row['id']) {
                            echo "<option selected value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        } else {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                    }
                }
                ?>
            </select>

            <div class="col-3 mb-2">

                <?php

                // Secondo metodo con funzione JavaScript
                echo '<div style="cursor: pointer;" onclick="openFileInput();">';
                echo '<img src="' . $post['image'] . '" alt="Inserisci immagine" class="w-100 pt-4" id="newImage">';
                echo '</div>';
                echo '<input type="file" name="newImage" id="fileInput" style="display: none;" onchange="updateImage(this);">';

                ?>

            </div>

            <button type="submit" class="btn btn-primary">Salva Modifiche</button>

        </form>
</body>
<script>
    function openFileInput() {
        document.getElementById('fileInput').click();
    }
    function updateImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('newImage').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</html>