<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Header</title>
</head>

<body>
    <header>
        <!-- NAV -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PHP MyBlog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse flex-grow-0 " id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center ">
                        <?php
                        if (isset($_SESSION["username"])) {
                            echo '<span class="navbar-text fst-italic align">Benvenuto, ' . $_SESSION["username"] . '!</span>';
                            echo '<a class="nav-link" href="dashboard.php"><button type="button" class="btn btn-success">Dashboard</button></a>';
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="<?php echo isset($_SESSION["username"]) ? 'logout.php' : 'login.php' ?>">
                                <button type="button"
                                    class="btn btn-danger"><?php echo isset($_SESSION["username"]) ? 'Logout' : 'Login' ?></button>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </header>
</body>

</html>