/**
 * Created by Yaron on 27-11-2015.
 */



var posx = 0;
var posy = 0;
var isTop = false;
var headerAngle = 0;
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
            if(Math.floor(Math.random() * 2)+1 == 1){
                img.src = "img/brokenglass1.png";
            }else{
                img.src = "img/brokenglass2.png";
            }
            img.style.position = "absolute";

            img.style.left = posx+"px";
            img.style.top = posy+"px";

            img.style.width = "100px";

            var angle = Math.floor(Math.random() * 360);
            img.style.transform = "translate(-50%,-50%) rotate("+angle+"deg)";

            img.setAttribute("class", "broken");

            document.querySelector(".header").appendChild(img);
        }
    },true);

    document.querySelector(".header h2").onclick = function (){

        var header = document.querySelector(".header h2");
        header.style.transition = "500ms";
        headerAngle += 360;
        header.style.transform = "rotate("+headerAngle+"deg)";

    };

});

function mobileMenu(t){
    window.location.href = t.value;
}
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
