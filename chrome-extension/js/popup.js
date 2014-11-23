var $ = jQuery;
var $evaluateButton = $('#evaluate-button');
var spinnerTarget = document.getElementById('spinner');
var opts = {
    lines: 7, // The number of lines to draw
    length: 0, // The length of each line
    width: 5, // The line thickness
    radius: 7, // The radius of the inner circle
    corners: 0.7, // Corner roundness (0..1)
    rotate: 0, // The rotation offset
    direction: 1, // 1: clockwise, -1: counterclockwise
    color: '#000', // #rgb or #rrggbb or array of colors
    speed: 1, // Rounds per second
    trail: 81, // Afterglow percentage
    shadow: false, // Whether to render a shadow
    hwaccel: false, // Whether to use hardware acceleration
    className: 'spinner', // The CSS class to assign to the spinner
    zIndex: 2e9, // The z-index (defaults to 2000000000)
    top: '50%', // Top position relative to parent
    left: '50%' // Left position relative to parent
};
var spinner = new Spinner(opts);
var currentUrl;

// gets current url - getSelected is asynchronous
chrome.tabs.getSelected(null, function(tab) {
    currentUrl = tab.url;
});

$evaluateButton.on("click", function() {
    if (!currentUrl) {
        return;
    }

    var request = new XMLHttpRequest();

    request.open("GET", "http://server.virtual/evaluate" + "?url=" + currentUrl, true);
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            spinner.stop();
            showResults(request.responseText);
        }
    };
    request.timeout = 0;

    request.send();

    spinner.spin(spinnerTarget);
});

function showResults(data) {
    var jsonResponse  = JSON.parse(data);
    var $results      = $('#results');
    var $resultsTable = $('#results-table');
    var websiteData   = jsonResponse['website'];
    var attributes    = jsonResponse['attributes'];
    var explanations  = jsonResponse['explanations'];

    // clear
    $resultsTable.find('tr:not(:first)').remove();

    for (var attribute in websiteData) {
        var influenceClass = 'x-mark mark';
        var bestValue = null;

        // show rating
        if (attribute === 'rating') {
            $results.find('.rating > .score').html(websiteData[attribute]);
            continue;
        }

        // bestValue does not exist for 'rating'
        bestValue = attributes[attribute]['bestValue'];

        if (parseFloat(explanations[attribute]) >= 0) {
            influenceClass = 'check-mark mark';
        }

        if (parseInt(attributes[attribute]['contribution']) === 0) {
            bestValue = 'It does not matter.';
        }

        $resultsTable.find('tr:last').after(
            '<tr>' +
                '<td>' + attributes[attribute]['description'] + '</td>' +
                '<td>' + websiteData[attribute] + '</td>' +
                '<td><span class="' + influenceClass + '"></span></td>' +
                '<td>' + bestValue + '</td>' +
            '</tr>');
    }

    $results.show();

    // TODO: open results in new page
    // TODO: http://stackoverflow.com/questions/9949964/creating-a-chrome-extension-to-open-a-link-in-a-new-tab
    // TODO: if boolean (0,1), write true/false
}
