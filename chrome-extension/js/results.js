$(function() {
    var spinnerTarget = document.getElementById('spinner');
    var $spinnerText = $('.spinner-text');
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
    var request = new XMLHttpRequest();
    var currentUrl = window.localStorage.getItem("currentUrl");

    request.open("GET", "http://server.virtual/evaluate" + "?url=" + currentUrl, true);

    spinner.spin(spinnerTarget);
    $spinnerText.text('Processing ' + currentUrl);
    $spinnerText.show();
    favicon.change('/images/icon_busy.png');

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            spinner.stop();
            $spinnerText.html('<a href="' + currentUrl + '" target="_blank">' + currentUrl + '</a>');
            showResults(request.responseText);
            favicon.change('/images/icon.png');
        } else if (request.readyState == 4 && request.status == 404) {
            spinner.stop();
            $spinnerText.text('Sorry, something went wrong.');
            favicon.change('/images/icon.png');
        }
        console.log(request.readyState)
        console.log(request.status)
    };
    request.timeout = 0;
    request.send();

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

            if (parseFloat(attributes[attribute]['contribution']) === 0) {
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
    }

    $(window).unload(function() {
        var request = new XMLHttpRequest();
        var currentUrl = window.localStorage.getItem("currentUrl");

        request.open("GET", "http://server.virtual/stop-processing", true);
        request.send();
    });
});