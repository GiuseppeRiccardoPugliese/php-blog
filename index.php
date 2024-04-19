<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Posts</title>
</head>

<?php
include 'header.php';
?>

<body>

    <div class="container">

        <?php
        // Connessione al database
        $conn = new mysqli("localhost", "root", "root", "php-blog");

        // Controllo la connessione
        if ($conn->connect_error) {
            die("Connessione al database fallita: " . $conn->connect_error);
        }

        // Eseguo una query per recuperare i post
        $sql = "SELECT * FROM posts";
        $result = $conn->query($sql);

        // Controllo se ci sono risultati
        if ($result->num_rows > 0) {
            // Output dei dati per ogni riga di risultati
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="card my-2">
                    <div class="d-flex justify-content-center ">
                        <img src="<?php echo $row['image']; ?>" class="card-img-top p-2" alt="Post-Img" style="width: 18rem;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                        <p class="card-text"><?php echo $row['content']; ?></p>
                        <a href="show_post.php?id=<?php echo $row["id"]; ?>" class="btn btn-primary">Visualizza</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "Nessun post trovato.";
        }

        // Chiudo la connessione
        $conn->close();
        ?>


    </div>

</body>

</html>