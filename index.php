<?php
error_reporting(E_ALL ^ E_NOTICE);

session_start();

if (empty($_SESSION['Roli'])) {
    /**Login*/
    header('Location: Login/index.php');
    exit();
}

switch ($_GET['page']) {
    case 'userlist':
        $title = 'Userlist';
        $includeFile = 'user-list/index.php';
        $includeScript = 'user-list/scripts.js';
        break;
    case 'pagat':
        $title = 'Pagat';
        $includeFile = 'paga/index.php';
        $includeScript = 'paga/script.js';
        break;
    case 'Produktet':
        $title = 'Produktet';
        $includeFile = 'Produktet/index.php';
        $includeScript = 'Produktet/script.js';
        break;
    case 'profile':
        $title = 'Profili';
        $includeFile = 'profile/index.php';
        $includeScript = 'profile/script.js';
        break;
    case 'checkin':
        $title = 'Check In';
        $includeFile = 'CheckIn/index.php';
        $includeScript ='CheckIn/script.js';
        break;
    default:
        $title = 'Home';
        $includeFile = '';
}
//print_r($includeFile);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$title?></title>
    <link rel = "icon" type = "image/*" href = "images/home1.jpg">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="Produktet/style.css">
    <link rel="stylesheet" href="CheckIn/style.css">
    <link rel="stylesheet" href="user-list/style.css">
    <link href="profile/script.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</head>
<body>
<!--Navbar-->
<?php
include 'includes/menu.php';
if (file_exists($includeFile)) {
    include $includeFile;

}
?>
<script src="<?= $includeScript; ?>"></script>

</body>
</html>
