/**
 * Created by Yaron on 27-11-2015.
 */
var filter = "";
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$(document).ready(function(){
    $("."+months[new Date().getMonth()]).fadeIn();
    filter = months[new Date().getMonth()];
    $('#button').click(function(){
        scrollToContent(1000);
    });
    $('.filter ul li').each(function (){
        $(this).click(function(){
            scrollToTop(1000);
            $("."+filter).fadeOut();
            $("."+$(this).data("filter")).fadeIn();
            filter = $(this).data("filter");
            setTimeout(function(){
                scrollToContent(500);
            },500);
        });
    });
});
function scrollToContent(speed){

    $("body, html").stop().animate({
        scrollTop: eval($('#events').offset().top - 100)
    }, speed);
}
function scrollToTop(speed){
    $("body, html").stop().animate({
        scrollTop: 0
    }, speed);
}