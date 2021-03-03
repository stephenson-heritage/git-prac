<?php

$config_path = getcwd();
require_once("inc/config/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Template - " . $page_data["title"] ?></title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

    <?php


    $mainMenu = new Menu($page, "main");
    $mainMenu->dbLoad($db);
    echo $mainMenu;

    User::view(
        $logged_in,
        $create_account,
        isset($config_user["data"]) ? $config_user["data"] : []
    );

    // include the page we are on
    include("pages/" . $page_data["script"]);


    $footerMenu = new Menu($page, "footer");
    $footerMenu->dbLoad($db);
    echo $footerMenu;

    ?>

</body>

</html>