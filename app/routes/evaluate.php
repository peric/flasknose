<?php
$app = new \Slim\Slim();

$app->get('/evaluate/:name', function ($name) use ($app) {
    echo "Hello, $name";
})->name('evaluate');;

// TODO: send url in /evaluate
// TODO: add conditions to route
// TODO: Check documentation, it's awesome!

//$app->config(array(
//    'debug' => true,
//    'templates.path' => '../templates'
//));

//<?php
////GET variable
//$paramValue = $app->request->get('paramName');
//
////POST variable
//$paramValue = $app->request->post('paramName');
//
////PUT variable
//$paramValue = $app->request->put('paramName');

// TODO: docs -> http://docs.slimframework.com/


//// fetcher.php
//
//echo "<form action='fetcher.php' method='get'>";
//echo "Number values to generate: <input type='text' name='N' />";
//echo "<input type='submit' />";
//echo "</form>";
//
//if(isset($_GET['N']))
//{
//    $N = $_GET['N'];
//
//    // execute R script from shell
//    // this will save a plot at temp.png to the filesystem
//    exec("Rscript my_rscript.r $N");
//
//    // return image tag
//    $nocache = rand();
//    echo("<img src='temp.png?$nocache' />");
//}
