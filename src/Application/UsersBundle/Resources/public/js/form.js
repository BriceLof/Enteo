$(function(){
    //$('.format_input').parent().hide();
    $('.format_label').hide();
    $('.role_user').click(function() {
        console.log($(this).val())
        switch($(this).val()){
            case "ROLE_CONSULTANT" :
                $('.calendar_uri')[this.checked ? "show" : "hide"]();
                $('.calendar_id')[this.checked ? "show" : "hide"]();
                $('.format_label')[this.checked ? "show" : "hide"]();
                //$('.format_input').parent()[this.checked ? "show" : "hide"]();
                 //$('.format_presence')[this.checked ? "show" : "hide"]();
                break;
           /* case "ROLE_CONSULTANT" :
                break;  */     
        }
        
    });
});

