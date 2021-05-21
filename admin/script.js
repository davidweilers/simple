const simple = [

];

$(function() {

    $('table[name] tbody').each(function() {
        $.get('/admin/',function(data) {
            console.log(data);
        });
    });
});
