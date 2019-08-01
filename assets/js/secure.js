
$(function (e) {
    
    var set = {};
    var ajaxRequestOn = false;
    set.btn_spin_html = '<img src="'+bu+'assets/img/ring-alt.svg" width="25px"> Please wait...' ;

    $("#username").keypress(function (e) 
    {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            // $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
        }
    });

    $('form[name="login_form"]').submit(function(e)
    {
        e.preventDefault();

        if(ajaxRequestOn == true)
            return false;

        $(this).removeClass('error-border');
        $(this).parent().find('.text-error').remove();
        $(this).find('.error-box').css('display','none');
        
        if(!$('#username').val() || $('#username').val().length != 10){
            $('#username').parent().after('<div class="text-error text-left">Invalid Username</div>');
            return false;
        }

        if(!$('#password').val()){
            return false;
        }

        var formData = {};
        formData.username = $('#username').val();
        formData.password = $('#password').val();

        ajaxRequestOn = true;

        $btn = $(this).find('button[type="submit"]');
        var btn_text = $btn.html();
        $btn.html(set.btn_spin_html);
        // window.location=bu+'dashboard';
        $.ajax({
            type:'POST',
            dataType:'json',
            data: formData,
            url:bu+'secure/signin/',
            success:function(data){
                if(data.status == 200){
                    window.location=bu+data.redirect_url;
                }else{
                    $('#password').parent().after('<div class="text-error text-left">'+data.message+'</div>');
                    return false;
                }
            },
            complete: function(response){
                $btn.html(btn_text);
                ajaxRequestOn = false;
            }
        });
    });

}(jQuery));
    