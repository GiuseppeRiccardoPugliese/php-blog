<?php
session_start();

// Verifico se l'utente è loggato
if (!isset($_SESSION["username"])) {
    // Se l'utente non è loggato, redirect alla login
    header("Location: login.php");
    exit();
}

// Connessione al database
$conn = new mysqli("127.0.0.1", "root", "root", "php-blog");

// Controllo la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

//ID dell'utente dalla sessione
$user_id = $_SESSION["user_id"];

// Query per selezionare i post dell'utente loggato
$sql = "SELECT * FROM posts WHERE user_id = $user_id";
$result = $conn->query($sql);

?>

<!-- dashboard.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<?php
include 'header.php';
?>

<body>
    <h1 class="text-center m-2">Dashboard</h1>

    <h2 class="ms-2">I tuoi post:</h2>
    <div class="text-center my-3">
        <a class='mx-1' href="create_post.php"><button class="btn btn-info ">Crea nuovo post</button></a>
    </div>
    <table class="table">
        <thead class="text-center">
            <tr>
                <th>Titolo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody class="text-center align-middle ">
            <?php
            if ($result->num_rows > 0) {
                // Output dei post dell'utente
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>";
                    echo "<a class='mx-1' href='update_post.php?id=" . $row["id"] . "'><button class='btn btn-primary'>Modifica</button></a>";
                    echo "<a class='mx-1' href='delete_post.php?id=" . $row["id"] . "'><button class='btn btn-danger'>Elimina</button></a>";
                    echo "<a class='mx-1' href='show_post.php?id=" . $row["id"] . "'><button class='btn btn-success'>Mostra</button></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Nessun post trovato.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>