<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="client.php">Goodmart</a>
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>   
        <div class="collapse navbar-collapse mt-3 " id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <!-- <a class="nav-link" href="client.php">view Cart</a> -->
                </li>
                <li class="nav-item">
                    <?php
                    session_start();
                    if (isset($_SESSION['client_id'])) {
                        echo '<form method="post" action="logout.php">';
                        echo '<button type="submit" class="btn btn-warning mb-3">Logout</button>';
                        echo '</form>';
                    } else {
                        echo '<a class="btn btn-primary mb-3" href="client_index.php">Login</a>';
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
