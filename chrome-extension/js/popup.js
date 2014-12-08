$(function() {
    var $evaluateButton = $('#evaluate-button');

    // sets current url to the local storage
    chrome.tabs.getSelected(null, function(tab) {
        window.localStorage.setItem("currentUrl", tab.url);
    });

    $evaluateButton.on("click", function() {
        chrome.tabs.create({'url': chrome.extension.getURL('results.html')}, function(tab) {
            // Opens new tab
        });
    });
});
