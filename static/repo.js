// Create a VideoRepo object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (typeof window.VideoRepo !== 'object') {
    window.VideoRepo = {};
}

(function () {
    'use strict';

    var url,
        cameras,
        videos,
        lastTimestamp,
        firstTimestamp;

// If the VideoRepo object does not yet have a getMore method, give it one.

    if (typeof VideoRepo.getMore !== 'function') {
        url = "http://bodyflight.arcanel.se/history.html";

        VideoRepo.getMore = function (foo) {

            var i;
            firstTimestamp = '';
            lastTimestamp = '';

            return JSON.stringify(foo);
        };
    }
}());
