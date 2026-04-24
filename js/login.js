import swal from 'sweetalert2';
import jQuery from 'jquery';

let $ = jQuery;
$(function() {
    $("#spanMostrar").on("click", function(){
        let elementInput= $("#login-pass");
        let elementIcon= $("#iconMostrar");
        if (elementIcon.hasClass("active")) {
            elementIcon.removeClass("active");
            elementIcon.html("visibility_off");
            elementInput.prop("type","password");
        } else {
            elementIcon.addClass("active");
            elementIcon.html("visibility");
            elementInput.prop("type","text");
        }
    });

    $("#frm-login").on("submit", function (e) {
                    let url = 'login_control';
                    let loginName = $("#login-name").prop("value");
                    let loginPass = $("#login-pass").prop("value");
                    let datos = 'UserName=' + loginName + '&UserPass=' + loginPass;
                    e.preventDefault();
                    let request = jQuery.ajax({
                        type:"POST",
                        url : url,
                        async: true,
                        contentType: 'application/x-www-form-urlencoded',
                        data: datos,
                        success : function (data, status, requestHttp) {
                            if (requestHttp.responseJSON.redirect) {
                                window.location.href = "/home";
                            } else if (requestHttp.responseJSON.MensajeError) {
                                swal.fire(requestHttp.responseJSON.MensajeError, '', 'warning');
                            }
                        }
                    });
        
    })
});
