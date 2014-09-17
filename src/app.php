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

        $urlClear  = preg_replace('/[^\w\s!?]/', '', $url);
        $parsedCsv = sprintf('%s/%s.csv', PARSED_WEBSITES_DIRECTORY, $urlClear);

        $response = array();

        // Website should be always parsed from the beginning
        runParser($url);

        // continue just if csv file is there
        if ($app['filesystem']->exists($parsedCsv)) {
            runEvaluator($parsedCsv);
            // No. 1
            // TODO: R script (needs Rdata)
            // TODO: Write R script that takes csv file as parameter
            // TODO: reads values for 20 best attributes (evaluated by relieff etc)
            // TODO: sends this data to method that predicts final rating
            // TODO: returns rating for website, and optimal values for all attributes
            // TODO: website data is saved to csv file
            // TODO: optimal values should be saved in some other csv file
            // TODO: ...

            // No. 2
            // TODO: read csv files (for website data and optimal values)
            // TODO: pack them in proper JSON format and return it back to the frontend app

            // Random
            // TODO: first, make evaluations and stuff and then finish R script evaluate.R

            // TODO:
            // TODO: Read optimal values from optimal.csv
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

            $response = array(
                'website' => array(
                    'url' => 'http://www.moonleerecords.com',
                    'text' => 15000,
                    'img' => 23
                ),
                'optimal' => array(
                    'text' => 3000,
                    'img' => 15
                )
            );
        }

        //return "||||||||||||||| just testing |||||||||||||||";

        //return $app->json($response);

        return "bla";
    }

    return $app->json($error, 404);
})->bind('evaulate_url');

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

// methods

function runParser($url)
{
    $parser = new Process(sprintf('cd %s && python wparser.py %s', WPARSER_DIRECTORY, $url));
    $parser->run();

    echo $parser->getOutput();
    echo $parser->getErrorOutput();

    echo $parser->getExitCodeText();

    // TODO: remove all echos
}

function runEvaluator($csvFile)
{
    $csvFile = '../' . $csvFile;

    $parser = new Process(sprintf('cd %s && Rscript evaluate.R %s', R_DIRECTORY, $csvFile));
    $parser->run();

    echo $parser->getOutput();
    echo $parser->getErrorOutput();

    echo $parser->getExitCodeText();
}

function getOptimalValues()
{
    // TODO:
}