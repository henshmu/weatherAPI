<?php
session_start();
$data = $_SESSION['data'] ?? NULL;
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Insta Weather | My saved forecast</title>
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
                        <input type="text" class="form-control text_origin" placeholder="Choose a city" name="city">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info bg-opacity" type="submit">Get from API</button>
                        </div>
                    </div>
                </form>
                <form method="POST" action="app/viewMyWeather.php" class=" mt25">
                    <input type="hidden" class="form-control text_target " name="city">
                    <button class="btn btn-outline-success bg-opacity" type="submit">Get from DB</button>
                </form>
                <div class="col-md-3"></div>
            </div>
        <!-- END of Forms -->
            
        <!-- Data from DB -->
            <ol>
                
            <?php
                if ($data != NULL ): 
                    if($_SESSION['is_array'] == TRUE) :                                                  
                        foreach(array_reverse($data) as $weather):
            ?>

                            <div class="col-md-12 bg-opacity">
                                <table class="table">                                                    
                                    <th><li>City</li></th>
                                    <th>Updated at</th>
                                    <th>Min temp</th>                    
                                    <th>Max temp</th>                                           
                                    <th>Wind speed</th>                       
                                    <tr>
                                        <td><?= $weather['c_city_name'] ?></td>
                                        <td><?= $weather['updated_at'] ?></td>
                                        <td><?= $weather['t_temp_min'] ?> &#8451</td>
                                        <td><?= $weather['t_temp_max'] ?> &#8451</td>
                                        <td><?= $weather['w_speed'] ?> Km/h</td>                                                            
                                    </tr>                                                                        
                                </table>
                            </div>

                <?php
                     endforeach; 
                     unset($_SESSION['data']);                                  // Clears the data, after displaying it, for the next search.
                     else:                         
                ?>   
                                
                 <div class="col-md-12 bg-opacity">
                    <table class="table">  
                        <th><li>Updated at</li></th>
                        <th>City</th>
                        <th>Datetime</th>
                        <th>Min temp</th>                    
                        <th>Max temp</th>                                           
                        <th>Wind speed</th>                        
                        <tr>
                            <td><?= $data['updated_at'] ?></td>
                            <td><?= $data['c_city_name'] ?></td>
                            <td><?= $data['t_date_time'] ?></td>
                            <td><?= $data['t_temp_min'] ?> &#8451</td>
                            <td><?= $data['t_temp_max'] ?> &#8451</td>
                            <td><?= $data['w_speed'] ?> Km/h</td>                                                            
                        </tr>                          
                    </table>
                </div>
                                
                <?php         
                     endif;
                     unset($_SESSION['data']);                                  // Clears the data, after displaying it, for the next search.
                     endif;
                ?>
                
                </ol>  
        <!-- END of Data from DB -->              
        </main>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>       
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" ></script>
        <script src="js/script.js" type="text/javascript"></script>
    </body>
</html>
