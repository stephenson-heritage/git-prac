

<?php

function addImage($db, $filename, $description="") { 

    $q = "INSERT INTO `image` (`imageFile`, `description`) VALUES (:filename, :description);";
    $pdo_image_insert = $db->prepare($q);


    $pdo_image_insert->execute(["filename"=>$filename, "description"=>$description]);
    return $pdo_image_insert->rowCount();
}


if(isset($_POST["upload"]) 
    && isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
    // upload form submitted
    echo "file uploaded!";
    //var_dump($_FILES);
    $fi = $_FILES["image"];

    $file_info = new finfo(FILEINFO_MIME_TYPE);
    $type = $file_info->file($fi["tmp_name"]);
   
    $allowed_types = ["image/jpeg", "image/png"];
    
    if(in_array($type, $allowed_types)) {

        $ext = "";
        switch($type) {
            case "image/jpeg":
                $ext = ".jpg";
                break;
            case "image/png":
                $ext = ".png";
                break;
        }

        $filename = md5(time()).$ext;
        $description = isset($_POST["description"]) ? $_POST["description"] : "";

        move_uploaded_file($fi["tmp_name"],dirname(__DIR__).'/img/'.$filename);
        addImage($db, $filename,$description);

    }

    
    

    

} else {
    // no upload form submission
?>
<form method="post" action="" enctype="multipart/form-data">

    <h3>Image upload!</h3>
    <div>
        <span>Choose an image:</span>
        <input type="file" name="image" />
    <div>
    <div>
        <span>Describe the image:</span>
        <textarea name="description"></textarea>
    <div>
    
    <input type="submit" name="upload" value="Upload" />

</form>

<?php 
}
?>