$(function() {

    // when the link is clicked
    $('#changeTitle').click(function() {
    
        // initiate an ajax request
        $.get('hello.php', function(data) {
            
            // set the server's response data into the title element
            $('#title').text(data);
            
            // update the subtitle's classname and text
            $('#subtitle').removeClass('not-updated').addClass('updated').text('It has been updated!');
            
        });
        
    });
    
}); 
