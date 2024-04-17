<?php
session_start();

// Connessione al database
$conn = new mysqli("localhost", "root", "root", "php-blog");

// Controllo la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// ID del post dalla query string
if (isset($_GET["id"])) {
    $post_id = $_GET["id"];
} else {
    // Se l'id del post non è specificato, redirect alla dashboard
    header("Location: dashboard.php");
    exit();
}

// Query per selezionare il post dall'ID specificato
$sql = "SELECT * FROM posts JOIN categories ON posts.category_id = categories.id WHERE posts.id = $post_id";

$result = $conn->query($sql);

// Verifico se il post esiste
if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    // Se il post non esiste, redirect alla dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Post</title>
</head>

<?php
include 'header.php';
?>

<body>
    <h1 class="text-center m-2">Dettagli Post</h1>
    <div class="container">
        <div class="card text-bg-light my-2">
            <img src="<?php echo $post['image']; ?>" class="card-img-top" alt="Post-Img">
            <div class="card-body">
                <h5 class="card-title"><?php echo $post['title']; ?></h5>
                <p class="card-text"><?php echo $post['content']; ?></p>
                <!-- <small class="card-subtitle"><?php echo $post['name']; ?></small> -->
                <footer class="card-text blockquote-footer">Categoria: <cite
                        title="Source Title"><?php echo $post['name']; ?></cite></footer>
            </div>
        </div>
        <!-- Altri dettagli del post... -->
    </div>
</body>

</html>