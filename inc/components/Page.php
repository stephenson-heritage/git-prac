<?php

class Page {

    static function getCurrentPage($db) {
        $page = "home";

        if(isset($_GET["p"])){
            $tempPage = $_GET["p"];
            $tempPage = strtolower(strip_tags($tempPage));
            $page = $tempPage;
        }

        $q = 'SELECT `pageKey`,`title`,`script`,`dateModified` FROM `page` WHERE pageKey = ?';
        $get_page = $db->prepare($q);

        $get_page->execute([$page]);
        $page_data = [];

        if($page_data = $get_page->fetch()) {    

        } else {
            $page = "home";
            $get_page->execute([$page]);
            $page_data = $get_page->fetch();
        }

        return $page_data;
    }

    

}

?>