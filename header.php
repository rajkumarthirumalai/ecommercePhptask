<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Goodmart Admin</a>
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>   
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="user_management.php">Edit User</a>
                </li>
                <li class="nav-item">
                    <form method="post" action="logout.php">
                        <button type="submit" class="btn btn-warning mb-3">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
