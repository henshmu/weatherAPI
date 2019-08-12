<?php
session_start();
$_SESSION['err'] = $_SESSION['err'] == 'error' ? 'error' : 'no error';
$weather = $_SESSION['data'] ?? NULL;
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Insta Weather</title>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/styles.css" rel="stylesheet">
        <style>
            body{
                background-image: url("img/bg.jpg");
            }
        </style>
    </head>

    <body>
        <!-- Nav bar -->
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="index.php">Insta Weather</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="app/viewMyWeather.php">My saved weather</a>
                    </li>
                </ul>                
            </div>
        </nav>
        <!-- END of Nav bar -->        
        
        <main role="main" class="container">
        <!-- Forms -->    
            <div class="row">
                <div class="col-md-3"></div>
                <form method="GET" action="app/weather.php" class="mt25">
                    <div class="input-group mb-3 mx-auto ">
                        <input type="text" class="form-control text_origin" placeholder="Choose a city" name="city" required="">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info bg-opacity" type="submit">Get from API</button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="app/viewMyWeather.php" class="mt25">
                    <input type="hidden" class="form-control text_target " name="city">
                    <button class="btn btn-outline-success bg-opacity" type="submit">Get from DB</button>
                </form>
                <div class="col-md-3"></div>
            </div>
            <!-- END of Forms -->
            
            <!-- Data from DB -->
            <ol>
                
            <?php
                if ($weather != NULL && $weather['cod'] == 200): 
                    $saveHere = 0;                                              // Limiter, to save the first forecast.
                     $start = reset($weather['list']);                          // Gets the first array.
                     $start = $start['dt_txt'];                                 
                     $end = end($weather['list']);                              // Gets the last array.
                     $end = $end['dt_txt'];
            ?>
                            
                <div class="row">                   
                    <div class="col-md-5 bg-opacity period">
                        <h1>
                            <?= ucwords($_SESSION['city']); ?>
                        </h1>
                        <h4>Period</h4>
                        <p>
                            Starts at: <?= $start ?><br>
                            Ends at: <?= $end; ?>                        
                        </p>
                    </div>
                </div>
                                
            <?php
                foreach($weather['list'] as $weather):
            ?>

                <div class="col-md-12 bg-opacity">
                    <table class="table">                                                    
                        <th><li></li></th>
                        <th>Datetime</th>
                        <th>Min temp</th>                    
                        <th>Max temp</th>                                           
                        <th>Wind speed</th>                       
                        <tr>
                            <td><img src="http://openweathermap.org/img/wn/<?= $weather['weather'][0]['icon'] ?>.png"></td>
                            <td><?= $weather['dt_txt'] ?></td>
                            <td><?= $weather['main']['temp_min'] ?> &#8451</td>
                            <td><?= $weather['main']['temp_max'] ?> &#8451</td>
                            <td><?= $weather['wind']['speed']*3.6 ?> Km/h</td>                                                            
                        </tr>
                        <?php if($saveHere == 0): ?>
                        <!-- Save forecast -->
                        <tr>
                            <td colspan="5">
                                <form action="app/saveWeather.php" method="POST">                                    
                                    <input type="hidden" name="city" value="<?= $_SESSION['city'] ?>">
                                    <input type="hidden" name="datetime" value="<?= $weather['dt_txt'] ?>">
                                    <input type="hidden" name="tmin" value="<?= $weather['main']['temp_min'] ?>">
                                    <input type="hidden" name="tmax" value="<?= $weather['main']['temp_max'] ?>">
                                    <input type="hidden" name="speed" value="<?= $weather['wind']['speed'] ?>">
                                <button type="submit" class="btn btn-success">Save forecast</button>
                                </form>
                            </td> 
                        </tr>
                        <!-- END of Save forecast -->
                        <?php
                            endif; 
                            $saveHere++;
                        ?>                                                
                    </table>
                </div>

                <?php
                     endforeach;
                     unset($_SESSION['data']);     // Clears the data, after displaying it, for the next search.
                ?>
                
                </ol>
                <!-- END of Data from DB -->
                <!-- Errors -->
                <?php
                    elseif($_SESSION['err'] == 'error'):                
                ?>
                <div class="col-md-3"></div>
                <div class="bg-warning warning">
                    There was an error, please try again.
                </div>
                <div class="col-md-3"></div>
                <?php  
                    $_SESSION['err'] = 'no error';  // Resets the error back to default after displaying it.
                    endif;
                ?>
                <!-- END of Errors -->
        </main>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>       
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" ></script>
        <script src="js/script.js" type="text/javascript"></script>
    </body>
</html>


