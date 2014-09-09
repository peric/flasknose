<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

$app->get('/evaluate', function(Request $request) use ($app) {
    $error = array('message' => 'Url is not valid.');

    $url = $request->get('url');

    if ($url) {
        // check if url is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return $app->json($error, 404);
        }

        $urlClear = preg_replace('/[^\w\s!?]/', '', $url);

        // Just use it as any Silex service
        /*if (!$app['filesystem']->exists('any-absolute-or-relative-path...')) {
        }*/

        // TODO before processing, check if it already exists in CSV or somewhere
        runParser($url);


        // TODO: after parsing, send parsed data to R script which will evaluate website (needs Rdata)

        // TODO: create R script which will update hypothesis on daily basis

        // TODO: return all that data to the extension


        // TODO: decide about response format
        $testResponse = array('response' => 'testing');

        return "||||||||||||||| just testing |||||||||||||||";

        //return $app->json($testResponse);
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


function runParser($url)
{
    $parser = new Process(sprintf('cd %s && python wparser.py %s', WPARSER_DIRECTORY, $url));
    $parser->run();

    echo $parser->getOutput();
    echo $parser->getErrorOutput();

    echo $parser->getExitCodeText();
}

//function csv()
//{
//    $records = $app['db']->fetchAll($sql);
//
//    header("Content-Type: text/csv");
//    header("Content-Disposition: attachment; filename=partial_customers.csv");
//    header("Pragma: no-cache");
//    header("Expires: 0");
//
//    $output = fopen("php://output", "w");
//
//    foreach ($records as $row)
//        fputcsv($output, $row, ',');
//
//    fclose($output);
//    exit();
//}