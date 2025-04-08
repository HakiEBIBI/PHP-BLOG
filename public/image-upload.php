<?php
function uploadImage($image)
{
    $targetDir = "image/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($image["name"]);
    $targetFile = $targetDir . $fileName;

    if (!getimagesize($image["tmp_name"])) {
        return ["error" => "Le fichier n'est pas une image."];
    }

    if (!in_array(strtolower(pathinfo($image["name"], PATHINFO_EXTENSION)), ["jpg", "jpeg", "png"])) {
        return ["error" => "Seuls les formats JPG, JPEG, PNG sont acceptés"];
    }

    if (move_uploaded_file($image["tmp_name"], $targetFile)) {
        return ["success" => true, "path" => $targetFile];
    }

    return ["error" => "Une erreur est survenue lors de l'upload"];
}