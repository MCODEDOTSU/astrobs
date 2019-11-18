jQuery(document).ready(function(){
    $.post('http://'+window.location.hostname+'/poll/poll/get', {}, function(data){
        $('#question').html(data);
    });
});

function pollSend(e)
{
    var poll_value = $('input[name=answer]:checked', $(e).parent()).val();
    var poll_id = $('input[name=answer]:checked', $(e).parent()).attr('poll');

    //$.cookie('poll'+poll_id, poll_value);

    pollAjax('http://'+window.location.hostname+'/poll/poll/send', {
        'value':poll_value,
        'poll_id':poll_id
    });
}

function pollAjax(to,data)
{
    $.post(to, data, function(obj){
        $('#poll'+data.poll_id).remove();
        $(obj).appendTo( $('#question'));
    });
}