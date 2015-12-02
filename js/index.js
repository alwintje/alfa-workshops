/**
 * Created by Yaron on 27-11-2015.
 */




$(document).ready(function(){
    $('#button').click(function(){
        scrollToContent(1000);
    });
});
$(document).scroll(function(){
    var padding = 15 - $(this).scrollTop() / 20;
    padding = padding < 3 ? 3 : padding;
    $(".menu").css({padding: padding+"px 0"});
    if(document.querySelector(".header h2") == undefined){
        alert("Deze website is gemaakt door Yaron Lambers en Alwin Kroesen");
    }
    document.querySelector(".header").onclick = function(e){
        var img = document.createElement("img");
        img.src = "http://i318.photobucket.com/albums/mm429/allenjeffries/private/broken-glass-psd44132.png";
        e.target.appendChild(img);
    };
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
