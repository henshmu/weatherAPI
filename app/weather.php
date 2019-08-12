<?php
session_start();

$city =  strtolower(trim($_GET['city'])) ?? NULL;

if ($city) {        // Checks if the user's input is valid
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'api.openweathermap.org/data/2.5/forecast?q=' . 
            $city.'&units=metric&APPID=2c433b964d3059aa9c80d3c85c23f3d2');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $data = json_decode($data,true);
    curl_close($ch);        

    
    if ($data['cod'] == 200) {
        $_SESSION['data'] = $data;  
        $_SESSION['city'] = $city;
        return header('location:../index.php');
    } else { 
        $_SESSION['err'] = 'error';       
        return header('location:../index.php');
    }
} else { 
    $_SESSION['err'] = 'error';
    return header('location:../index.php');
}