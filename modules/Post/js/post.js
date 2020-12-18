$('.add-post-form').on('submit',function(event) {
    event.preventDefault();
    var form = jQuery(this);

    var title  = jQuery(form).find('.title').val();
    var content  = jQuery(form).find('.content').val();
    

    $.post('/modules/Post/PostAjax.php',
        {function : "AddPost", title: title, content: content },
        function(response) {
           console.log(response);
       });
 

});




