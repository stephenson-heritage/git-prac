<?php
require_once("secrets.php");
require_once("dbconn.php");
require_once(dirname(__DIR__).'/components/Page.php');

$page_data = Page::getCurrentPage($db);
$page = $page_data["pageKey"];

require_once(dirname(__DIR__).'/components/User.php');
require_once(dirname(__DIR__).'/components/Menu.php');

$config_user = User::login($db);
$create_account = $config_user["create"];
$logged_in = $config_user['loggedin'];

if($logged_in && isset($_GET["logout"])) {
    $config_user = User::logout($db, $config_user["data"]["username"]);
    $logged_in = $config_user['loggedin'];
}

?>