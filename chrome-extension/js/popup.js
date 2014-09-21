var evaluateButton = document.getElementById('evaluate');
var request = new XMLHttpRequest();
var currentUrl;

// gets current url - getSelected is asynchronous
chrome.tabs.getSelected(null, function(tab) {
    currentUrl = tab.url;
});

evaluateButton.onclick = function() {
    if (!currentUrl) {
        return;
    }

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
    var target = document.getElementById('spinner');
    var spinner = new Spinner(opts);

    var request = new XMLHttpRequest();

    request.open("GET", "http://server.virtual/evaluate" + "?url=" + currentUrl, true);
    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            spinner.stop();
            showResults(request.responseText);
        }
    };

    request.send();

    spinner.spin(target);
};

function showResults(data) {
    // TODO: show results on the frontend
    var jsonResponse = JSON.parse(data);

    console.log(jsonResponse);
}
