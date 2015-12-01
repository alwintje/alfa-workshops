/**
 * Created by Yaron on 27-11-2015.
 */
$(document).ready(function(){

    $('#button').click(function() {

        $("body, html").animate({
            scrollTop: eval($('#events').offset().top - 100)
        }, 1000);
    });
});