<?php

$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
$options[PDO::ATTR_EMULATE_PREPARES] = false;

$db;

try{
    $db = new PDO(getDSN(), $user, $pass, $options);
} catch(PDOException $err) {
    echo $err->getMessage();
}


?>