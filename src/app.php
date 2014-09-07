<?php
use Symfony\Component\HttpFoundation\Response;

$app->match('/', function() use ($app) {
    return 'Hello man!';
})->bind('home');

$app->match('/evaluate/{url}', function($url) use ($app) {
    return $url;
})->bind('evaulate_url');

// TODO: send url in /evaluate
// TODO: add conditions to route
// TODO: Check documentation, it's awesome!

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

$app->error(function (\Exception $e, $code) {
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});
