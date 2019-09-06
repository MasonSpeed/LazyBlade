/*
|--------------------------------------------------------------------------
| File inputs
|--------------------------------------------------------------------------
|
| This file contains all the functions and events surrounding
| file inputs and thumbnails
|
*/

function consoleSuccess(title, message) {
    console.log("%cSuccess: " + "%c" + message, "color:green;font-weight:bold;", "color:inherit;font-weight:normal;");
}

function consoleError(title, message) {
    console.log("%cError: " + "%c" + message, "color:red;font-weight:bold;", "color:inherit;font-weight:normal;");
    return false;
}

function renderBlade(template, args, elem, successCallback) {
    $.ajax({
        type: "POST",
        cache: false,
        url: "/blade",
        headers: {
            'X-CSRF-Token': csrf
        },
        data: {blade: template, data: JSON.stringify(args)},
        success: function (response) {
            if (typeof successCallback === "function") {
                consoleSuccess("Success", "Running success callback");
                successCallback(response, elem);
            } else {
                console.log("No success callback but it worked.")
            }
        },
    });
}

$(document).ready(function () {


    $.fn.isInViewport = function () {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };


    $("[data-render-blade]").each(function () {
        let template = $(this).attr("data-render-blade");
        let args = JSON.parse(decodeURIComponent($(this).attr("data-render-args")));
        let lazy = $(this).data("render-lazy");
        let elem = $(this);
        if (lazy === true) {
            $(window).on('resize scroll load', function () {
                if (elem.visible() && !elem.hasClass("rendered")) {
                    renderBlade(template, args, elem, function (response, elem) {
                        elem.replaceWith(response);
                    });
                    elem.addClass("rendered");
                }
            });
        } else {
            renderBlade(template, args, $(this), function (response, elem) {
                elem.replaceWith(response);
            });
        }
    });
});