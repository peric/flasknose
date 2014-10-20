<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

$app->get('/evaluate', function(Request $request) use ($app) {
    header('Access-Control-Allow-Origin: *');

    $url = $request->get('url');

    if ($url) {
        // check if url is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return $app->json(array('message' => 'Url is not valid.'), 404);
        }

        $url = preg_replace("/^https:/i", "http:", $url);

        $urlClear     = preg_replace('/[^\w\s!?]/', '', $url);
        $parsedCsv    = sprintf('%s/%s.csv', PARSED_WEBSITES_DIRECTORY, $urlClear);
        $evaluatedCsv = sprintf('%s/%s.csv', EVALUATED_WEBSITES_DIRECTORY, $urlClear);

        runParser($url);

        if (!$app['filesystem']->exists($parsedCsv)) {
            return $app->json(array('message' => 'Parsed csv file does not exist.'), 404);
        }

        runEvaluator($parsedCsv);

        if (!$app['filesystem']->exists($evaluatedCsv)) {
            return $app->json(array('message' => 'Evaluated csv file does not exist.'), 404);
        }

        $response = generateResponse($parsedCsv, $evaluatedCsv);

        return $app->json($response);
    }

    return $app->json(array('message' => 'Url is not valid.'), 404);
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

// runs phantomjs parser
function runParser($url)
{
    $parser = new Process(sprintf('cd %s && python wparser.py %s', WPARSER_DIRECTORY, $url));
    $parser->run();
}

// runs R evaluator
function runEvaluator($csvFile)
{
    $csvFile = '../' . $csvFile;

    $parser = new Process(sprintf('cd %s && Rscript evaluate.R %s', R_DIRECTORY, $csvFile));
    $parser->run();
}

// generates response
function generateResponse($parsedCsv, $evaluatedCsv)
{
    $attributesData = getAttributesData();
    $websiteValues  = getAttributeValues($parsedCsv, $attributesData);
    $optimalValues  = getOptimalValues();

    // TODO: get attribute data and send it to attribute values method

    $websiteValues['rating'] = getRating($evaluatedCsv);

    return array(
        'website'        => $websiteValues,
        'attributesData' => $attributesData,
    );
}

function getAttributesData()
{
    $attributesData = array();

    // read attributes names
    // TODO: Get attribute optimal data etc to here
    // TODO: Add optimal value here
    // TODO: Use optimal value to check "validity"



    $row = 1;
    if (($handle = fopen(ATTRIBUTES_CSV, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if($row == 1) {
                $row++;
                continue;
            }
            $attributesData[$data[0]]['description'] = $data[1];
        }
    }

    return $attributesData;
}

// gets array of attribute values from parsed csv file
function getAttributeValues($csvFile, $attributesData)
{
    // read parsed file
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        // it's always just one row here
        $row = 1;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if($row == 1) {
                $row++;
                continue;
            }

            $allValues = array(
                'url' => $data[0],
                'text' => $data[1],
                'html_elements' => $data[2],
                'headings' => $data[3],
                'paragraphs' => $data[4],
                'images' => $data[5],
                'font_families' => $data[6],
                'font_sizes' => $data[7],
                'links' => $data[8],
                'divs' => $data[9],
                'ids' => $data[10],
                'classes' => $data[11],
                'css_external' => $data[12],
                'css_internal' => $data[13],
                'css_inline' => $data[14],
                'css_declaration_blocks' => $data[15],
                'css_prefixes' => $data[16],
                'js_sources' => $data[17],
                'meta_tags' => $data[18],
                'has_meta_keywords' => $data[19],
                'has_meta_description' => $data[20],
                'rss' => $data[21],
                'import' => $data[22],
                'twitter_bootstrap' => $data[23],
                'html5_tags' => $data[24],
                'html5' => $data[25],
                'css_transitions' => $data[26],
                'flash' => $data[27],
                'page_weight' => $data[28],
                'media_queries' => $data[29],
                'conditional_comments' => $data[30],
                'included_multimedia' => $data[31],
                'minified_css' => $data[32],
                'font_families_list' => $data[33],
                'h1_font' => $data[34],
                'h2_font' => $data[35],
                'h3_font' => $data[36],
                'h4_font' => $data[37],
                'h5_font' => $data[38],
                'p_font' => $data[39],
                'a_font' => $data[40],
                'reset_css' => $data[41],
                'normalize_css' => $data[42],
                'css_pseudo_elements' => $data[43],
                'no_js' => $data[44],
                'html_errors' => $data[45],
                'colors' => $data[46],
                'color_palette' => $data[47],
                'dominant_color' => $data[48],
            );
            $values = array();

            foreach ($attributesData as $attribute => $data) {
                $values[$attribute] = $allValues[$attribute];
            }

            return $values;
        }
        fclose($handle);
    }

    return array();
}

// get rounded rating from evaluated csv file
function getRating($csvFile)
{
    $row = 1;
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if($row == 1) {
                $row++;
                continue;
            }

            // [0] url
            // [1] rating
            return round($data[1] * 10, 2);
        }

        fclose($handle);
    }

    return false;
}

// gets optimal values
function getOptimalValues()
{


    // TODO:
    // read from OPTIMAL_VALUES_CSV
    // TODO: Read optimal values from optimal.csv
    // TODO: maybe rename this method?

    return array();
}