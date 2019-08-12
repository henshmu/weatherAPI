<?php
session_start();

// Saving weather to DB
$dbh = new PDO('mysql:host=localhost;dbname=weather', 'root', '');
$_POST['city'] = ucwords( strtolower($_POST['city']));

$output = $dbh->prepare('SELECT c_city_name, c_id FROM city WHERE c_city_name = ?');
$output->bindParam(1, $_POST['city']);
$output->execute();
$row = $output->fetch(PDO::FETCH_ASSOC);                                        // Gets the city's id as an assoc array.

if (!$row) {                                                                    // Once $row = null means there isn't a city in the db with the same name. 
    $output = $dbh->prepare("INSERT INTO city VALUES ('', ? ,NOW(), NOW())");
    $output->bindParam(1, $_POST['city']);
    $output->execute();

    if ($id = $dbh->lastInsertId()) {                                           // If prev query was successful code will continue
        $output = $dbh->prepare("INSERT INTO temp VALUES ('', ? , ? , ? ,$id );"
                . " INSERT INTO wind VALUES ('', ? ,$id )");
        $output->bindParam(1, $_POST['tmax']);
        $output->bindParam(2, $_POST['tmin']);
        $output->bindParam(3, $_POST['datetime']);
        $output->bindParam(4, $_POST['speed']);
        $output->execute();
       
        $_SESSION['is_array'] = TRUE;
        header('location:../myWeather.php');
    }
} elseif ($row['c_city_name']== $_POST['city'] ) {                              // Once the $row = NUM the query will update the relevant colums
    $id = $row['c_id'];
    $output = $dbh->prepare("UPDATE city c, temp t, wind w SET c.updated_at = NOW(), "
                . "t.t_temp_max = ?, t.t_temp_min = ?, t.t_date_time = ?, w.w_speed = ? WHERE "
            . " c_id = $id AND t.t_city_id = $id AND w.w_city_id = $id");
        $output->bindParam(1, $_POST['tmax']);
        $output->bindParam(2, $_POST['tmin']);
        $output->bindParam(3, $_POST['datetime']);
        $output->bindParam(4, $_POST['speed']);
        $output->execute();
        
        header('location:../myWeather.php');
}
      