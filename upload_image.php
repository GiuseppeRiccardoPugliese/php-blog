<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["newImage"])) {
    // Controlla se l'upload è avvenuto senza errori
    if ($_FILES["newImage"]["error"] == UPLOAD_ERR_OK) {
        // Directory dove verranno salvate le immagini
        $uploadDir = 'uploads/';

        // Ottieni il percorso temporaneo del file caricato
        $tmpFilePath = $_FILES['newImage']['tmp_name'];

        // Ottieni il nome del file originale
        $fileName = $_FILES['newImage']['name'];

        // Genera un nome univoco per il file
        $filePath = $uploadDir . uniqid() . '_' . $fileName;

        // Sposta il file temporaneo nella directory di destinazione
        if (move_uploaded_file($tmpFilePath, $filePath)) {
            // Il caricamento del nuovo file è avvenuto con successo

            // Effettua l'aggiornamento del percorso dell'immagine nel database
            // Sostituisci questo con la tua logica per l'aggiornamento nel database
            $newImagePath = $filePath;
            // Supponiamo che tu abbia già l'ID del post
            $postId = $post['id'];
            // Esegui l'aggiornamento del percorso dell'immagine nel database
            // Sostituisci questo con la tua logica per l'aggiornamento nel database
            // $conn è la tua connessione al database
            $conn = new mysqli("localhost", "root", "root", "php-blog");

            // Controllo la connessione
            if ($conn->connect_error) {
                die("Connessione al database fallita: " . $conn->connect_error);
            }
            $sql = "UPDATE posts SET image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $newImagePath, $postId);
            $stmt->execute();
            $stmt->close();

            // Reindirizza l'utente alla pagina precedente
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } else {
            echo "Si è verificato un errore durante il caricamento del file.";
        }
    } else {
        echo "Si è verificato un errore durante l'upload del file.";
    }
}
?>