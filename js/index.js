/**
 * Created by Yaron on 27-11-2015.
 */

$("#events").click(function() {
    $('html, body').animate({
        scrollTop: $("#events").offset().top
    }, 2000);
});