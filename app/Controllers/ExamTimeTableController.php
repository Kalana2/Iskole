<?php
session_start();
require_once __DIR__ . '/../Core/Database.php';
$con = mysqli_connect("mysql-iskole.alwaysdata.net","iskole_admin","iskole+123","iskole_db");
if (isset($_POST['action'])){
    $grade = $_POST['grade' ];
    $image = $_FILES['exam_image']['grade'];



    $insert_image_query = "INSERT INTO exam_time_tables(grade,file_path) VALUES ('$grade','$image')";
    $insert_image_query_run = mysqli_query($dsn, $insert_image_query);




    if($insert_image_query_run){

        move_uploaded_file($_FILES['exam_image']['tmp_name'], '/../../public/uploadimages/'.$_FILES['exam_image']['grade']);
        $_SESSION['exam_tt_msg'] = "Exam Time Table uploaded successfully.";
    } else {
        $_SESSION['exam_tt_msg'] = "Failed to upload Exam Time Table.";
    }
}



?>