/**
 * Created by Yaron on 27-11-2015.
 */
$('#button').click(function() {
    $('body').animate({
        scrollTop: eval($('#' + $(this).attr('target')).offset().top - 70)
    }, 1000);
});