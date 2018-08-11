$('a.symbol-tab').click(function(id){

    var data = $(this).attr('tab-link')
    var panel = $(this).attr('data-subpanel')

    if($('#'+panel).html()==""){
        $('#'+panel).load(data);
    }
})
