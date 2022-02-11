<?php
include "header.php";
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="welcome.php">Welcome <?php echo $_SESSION['first_name'] ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <?php
            if ($_SESSION['Roli']=='admin')
echo ' <li class="nav-item active">
                <a class="nav-link" href="shto.php">User<span class="sr-only">(current)</span></a>
            </li>'
           ?>


            <li class="nav-item">
                <a class="nav-link" href="profili.php">Profili</a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">

                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>

        <a href="logout.php" style="margin: 2px" class="btn btn-primary">Logout</a>
    </div>
</nav>
