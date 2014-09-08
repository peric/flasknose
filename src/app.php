<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

$app->match('/', function() use ($app) {
    return 'Hello test!';
})->bind('home');

$app->get('/evaluate', function(Request $request) use ($app, $scriptsDirectory, $wparserDirectory) {
    // TODO before processing, check if it already exists in CSV or somewhere
    $error = array('message' => 'Url is not valid.');

    $url = $request->get('url');

    if ($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return $app->json($error, 404);
        }

        // TODO: Install python dependencies
        // TODO: Set up config and keep it in repo
        $process = new Process("cd $scriptsDirectory/$wparserDirectory && python wparser.py");
        $process->run();

        echo $process->getOutput();
        echo $process->getErrorOutput();

        // TODO: after parsing, send parsed data to R script which will evaluate website (needs Rdata)

        // TODO: create R script which will update hypothesis on daily basis

        // TODO: return all that data to the extension

        //

        //$nocache = rand();


        // TODO: decide about response format
        $testResponse = array('response' => 'testing');

        return $app->json($testResponse);

//        return "<img src='/exports/temp.png?$nocache' />";
    }
    return $app->json($error, 404);
})->bind('evaulate_url');

// TODO: Make proper stuff here
// TODO: send url in /evaluate
// TODO: add conditions to route
// TODO: Check documentation, it's awesome!

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
