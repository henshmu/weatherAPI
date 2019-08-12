<?php
session_start();

$dbh = new PDO('mysql:host=localhost;dbname=weather_api', 'root', '');

// Retrive data from db. (If $_POST) means the user has sent a request, otherwise display all saved forecasts.
if ($_POST) {    
    $_POST['city'] = strtolower($_POST['city']);
    $output = $dbh->prepare('SELECT c_id FROM city WHERE c_city_name = ?');
    $output->bindParam(1, $_POST['city']);
    $output->execute();
    $row = $output->fetch(PDO::FETCH_ASSOC);                                    // Gets the city's id as an assoc array.


    if (is_numeric($row['c_id'])) {                                             // Checking the respond of the query above, if it's ok continue to retrive the data
        $output = $dbh->prepare("SELECT t.t_temp_max, t.t_temp_min, t.t_date_time, w.w_speed, c.c_city_name, "
            . " c.created_at, c.updated_at FROM (( temp t INNER JOIN wind w ON "
            . " t.t_city_id= w.w_city_id) INNER JOIN city c ON c.c_id = w.w_city_id) WHERE w.w_city_id = ?");
        $output->bindParam(1, $row['c_id']);
        $output->execute();

        $row = $output->fetch(PDO::FETCH_ASSOC);                                       
    }

    $output = NULL;
    $dbh = NULL;

    if ($row) {
        $_SESSION['data'] = $row;
        $_SESSION['is_array'] = FALSE;
        return header('location:../myWeather.php');
    } else {
        $_SESSION['err'] = 'error';
        return header('location:../index.php');
    }
}else{
    // Get all saved forecasts
    $output = $dbh->prepare("SELECT t.t_temp_max, t.t_temp_min, w.w_speed, c.c_city_name, "
            . " c.created_at, c.updated_at FROM (( temp t INNER JOIN wind w ON "
            . " t.t_city_id= w.w_city_id) INNER JOIN city c ON c.c_id = w.w_city_id)");
    $output->setFetchMode(PDO::FETCH_ASSOC);
    $output->execute();
    $row = $output->fetchAll();

    $output = NULL;
    $dbh = NULL;
    if ($row) {
        $_SESSION['data'] = $row;   
        $_SESSION['is_array'] = TRUE;                                           // If TRUE, Validation will know where to display the content in the view.
        return header('location:../myWeather.php');
    } else {
        $_SESSION['err'] = 'error';
        return header('location:../index.php');
    }    
}


