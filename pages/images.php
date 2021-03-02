<?php

$pdo_image_query;
if(isset($_GET['q'])) {
    $search = $_GET['q'];

    echo "search for $search";

    $q = "SELECT `imageFile`,`description` FROM `image` WHERE `description` LIKE ?";
    $pdo_image_query = $db->prepare($q);
    $pdo_image_query->execute(["%".$search."%"]);

} else {
    $q = "SELECT `imageFile`,`description` FROM `image`";
    $pdo_image_query = $db->query($q);
}
    echo '<div class="gallery">';
    while($img = $pdo_image_query->fetch()) {
        $file = $img["imageFile"];
        $desc = $img["description"];

        echo '<div>';
        echo "<img src=\"img/$file\" />";
        echo "<caption>$desc</caption>";
        echo '</div>';
    }
    echo '</div>';

?>