<?php
include "config.php";

//Еще один пример spagetti кода, где мало используются константы и переменные с неговорящими именами

// print_r($_FILES);
$path_fullscale = "files/images_fullscale/{$_FILES['photo']['name']}";
$path_thumbnail = "files/images_thumbnails/{$_FILES['photo']['name']}";

if(move_uploaded_file($_FILES['photo']['tmp_name'], $path_fullscale)) {

    // Set a maximum height and width
    $width = 100;
    $height = 100;

    // Content type
    header('Content-Type: image/jpeg');

    // Get new dimensions
    list($width_orig, $height_orig) = getimagesize($path_fullscale);

    $ratio_orig = $width_orig/$height_orig;

    if ($width/$height > $ratio_orig) {
    $width = $height*$ratio_orig;
    } else {
    $height = $width/$ratio_orig;
    }

    // Resample
    $image_p = imagecreatetruecolor($width, $height);
    $image = imagecreatefromjpeg($path_fullscale);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

    // Output
    imagejpeg($image_p, $path_thumbnail);
    $image_size = filesize($path_fullscale);

    // Update header
    // header("Location: index.php");

}

$action = $_GET['action'];

if($action == 'delete') {
    $image_id = $_GET['id'];
    $query = "delete from photos where id=$image_id";
    if(mysqli_query($connect,$query)) {
        header("location: index.php");
    }
} elseif($action == 'add') {
    $query = "INSERT INTO photos VALUES (id, '{$_FILES['photo']['name']}', 'files/images_fullscale/', 'files/images_thumbnails/', {$image_size}, 0)";
    if(mysqli_query($connect,$query)) {
        header("location: index.php");
    }
} elseif($action == 'calc1' || $action == 'calc2') {
    $first = $_POST['first'];
    $second = $_POST['second'];
    $operation = $_POST['operation'];
    if($operation == 'div' && $second == 0) {
        header("Location: calc.php?res=Нельзя делить на ноль!");
    } elseif ($first && $second) {
        switch ($operation) {
            case 'add':
                $res = $first + $second; 
                break;
            
            case 'sub':
                $res = $first - $second;
                break;
            
            case 'mul':
                $res = $first * $second;
                break;
    
            case 'div':
                $res = $first / $second;
                break;
        }
        header("Location: calc.php?res={$res}&first={$first}&second={$second}&operation={$operation}");
    } else {
        header("Location: calc.php?res=Не выбрано число(а)!");
    }
} elseif($action == 'newpost') {
    $username = htmlspecialchars($_POST['username']);
    $post = htmlspecialchars($_POST['post']);
    if($username && $post) {
        $date = date('d.m.Y H:m');
        $query = "INSERT INTO feedback VALUES (id, '$username', '$post', '$date')";
        if(mysqli_query($connect,$query)) {
            header("location: feedback.php");
        }
    } else {
        echo 'Не указано имя или пустой отзыв!<br>';
        echo "<a href='feedback.php'>Вернуться назад</a>";
    }
}

?>