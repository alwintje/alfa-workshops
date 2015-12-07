/**
 * Created by Yaron on 27-11-2015.
 */



var posx = 0;
var posy = 0;
var isTop = false;
document.onmousemove = function(e){
    posx = e.clientX;
    posy = e.clientY;
};
$(document).ready(function(){
    isTop = $(document).scrollTop() == 0;
    $('#button').click(function(){
        scrollToContent(1000);
    });

    document.querySelector(".header").addEventListener("click", function(e){


        if(isTop){
            var img = document.createElement("img");
            img.src = "http://i318.photobucket.com/albums/mm429/allenjeffries/private/broken-glass-psd44132.png";
            img.style.position = "absolute";

            img.style.left = posx+"px";
            img.style.top = posy+"px";

            img.style.width = "100px";
            img.style.transform = "translate(-50%,-50%)";

            img.setAttribute("class", "broken");

            document.querySelector(".header").appendChild(img);
        }
    },true);
});
$(document).scroll(function(){
    isTop = $(this).scrollTop() == 0;
    console.log(isTop);

    var padding = 15 - $(this).scrollTop() / 20;
    padding = padding < 3 ? 3 : padding;
    $(".menu").css({padding: padding+"px 0"});
    if(document.querySelector(".header h2") == undefined){
        alert("Deze website is gemaakt door Yaron Lambers en Alwin Kroesen");
    }
    var glasses = document.querySelectorAll(".broken");
    for(var i=0; i<glasses.length;i++){
        //glasses[i].
    }
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
