'use strict';

let hasPhoneError = false;
let hasEmailError = false;

function enableDisableButton()
{
    if (!hasPhoneError && !hasEmailError) {
        $('form').find("button[type='submit']").prop('disabled',false);
    } else {
        $('form').find("button[type='submit']").prop('disabled',true);
    }
}

$('#personal-info-form').on('submit', function() {
    $("#registrationSubmitBtn").attr("disabled", true);
    $("#rightAngle").addClass('d-none');
    $(".spinner").removeClass('d-none');
    $("#registrationSubmitBtnTxt").text(signingUpText);
});

$("#phone").intlTelInput({
    separateDialCode: true,
    nationalMode: true,
    preferredCountries: [countryShortCode],
    autoPlaceholder: "polite",
    placeholderNumberType: "MOBILE",
    utilsScript: utilsJsScript
});

$("#verify-phone").intlTelInput({
    separateDialCode: true,
    nationalMode: true,
    //preferredCountries: [countryShortCode],
    autoPlaceholder: "polite",
    placeholderNumberType: "MOBILE",
    utilsScript: utilsJsScript
});

function updatePhoneInfo()
{
    let promiseObj = new Promise(function(resolve, reject)
    {
        $('#defaultCountry').val($('#phone').intlTelInput('getSelectedCountryData').iso2);
        $('#carrierCode').val($('#phone').intlTelInput('getSelectedCountryData').dialCode);

        if ($('#phone').val != '') {
            $("#formattedPhone").val($('#phone').intlTelInput("getNumber").replace(/-|\s/g,""));
        }
        resolve();
    });  
    return promiseObj;  
}

function updatePhoneExistInfo()
{
    let promiseObj = new Promise(function(resolve, reject)
    {
        $('#defaultCountry').val($('#verify-phone').intlTelInput('getSelectedCountryData').iso2);
        $('#carrierCode').val($('#verify-phone').intlTelInput('getSelectedCountryData').dialCode);

        if ($('#verify-phone').val != '') {
            $("#formattedPhone").val($('#verify-phone').intlTelInput("getNumber").replace(/-|\s/g,""));
        }
        resolve();
    });  
    return promiseObj;  
}

function checkDuplicatePhoneNumber()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: SITE_URL + '/register/duplicate-phone-number-check',
        dataType: 'json',
        cache: false,
        data: {
            'phone': $.trim($('#phone').val()),
            'carrierCode': $.trim($('#phone').intlTelInput('getSelectedCountryData').dialCode),
        }
    })
    .done(function(response)
    {
        if (response.status) {
            $('#duplicate-phone-error').show().addClass('error').html(response.fail);
            hasPhoneError = true;
            enableDisableButton();
        } else {
            $('#duplicate-phone-error').html('');
            hasPhoneError = false;
            enableDisableButton();
        }
    });
}

function checkExistPhoneNumber()
{
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: SITE_URL + '/register/check-phone-exist',
        dataType: 'json',
        cache: false,
        data: {
            'phone': $.trim($('#verify-phone').val()),
            'carrierCode': $.trim($('#phone').intlTelInput('getSelectedCountryData').dialCode),
        }
    })
    .done(function(response)
    {
        if (response.status) {
            $('#duplicate-phone-error').show().addClass('error').html(response.fail);
            
        } else {
            $('#duplicate-phone-error').html('');
           
        }
    });
}

// function send mobile check
function sendMobileCheck()
{
    hasPhoneError = true;
    enableDisableButton();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: SITE_URL + '/register/send-otp-phone',
        dataType: 'json',
        cache: false,
        data: {
            'phone': $.trim($('#verify-phone').val()),
            'carrierCode': $.trim($('#verify-phone').intlTelInput('getSelectedCountryData').dialCode),
        }
    })
    .done(function(response)
    {
        if (response.status) {
            $('#mobile-check-error').show().addClass('alert').html(response.message);
            hasPhoneError = false;
            enableDisableButton();
            setTimeout(function() {
                $('#mobile-check-error').hide().removeClass('alert').html('');
            }, 3000); 
        } else {
            $('#mobile-check-error').show().addClass('success-mess alert success').html(response.message);
            hasPhoneError = false;
            enableDisableButton();
            setTimeout(function() {
                $('#mobile-check-error').hide().removeClass('success-mess alert success').html('');
            }, 3000); 
        }
    });
}

var isSendCheck = true;

$( "#send-code-btn" ).on( "click", function() {
    if($("#verify-phone").val() == "") {
        var errorMessage = requiredText;
        var errorHTML = createAlert(errorMessage);
        document.getElementById("send-mb-notice").innerHTML = errorHTML;
 
        setTimeout(function() {
            closeAlert('send-mb-notice');
        }, 3000); 
        return;
    }

    if (isSendCheck) {
        sendMobileCheck();
        isSendCheck = false;

        setTimeout(function () {
            isSendCheck = true;
        }, 90000);
    } else {
       var errorMessage = waitMessage;

        var errorHTML = createAlert(errorMessage);
        document.getElementById("send-mb-notice").innerHTML = errorHTML;
        setTimeout(function() {
            closeAlert('send-mb-notice');
        }, 3000); 
    }
});

function createAlert(content) {
    return '<div class="alert">' +
           '<span class="closebtn" onclick="closeAlert();">&times;</span>' +
           '<strong>Error:</strong> ' + content +
           '</div>';
}

function closeAlert(id) {
    document.getElementById(id).innerHTML = '';
}



$( "form#verify-phone-form" ).on( "submit", function( event ) {
    event.preventDefault();
    activeOtpMobileCheck();
    
});

function activeOtpMobileCheck()
{
    hasPhoneError = true;
    enableDisableButton();
    if($("#verify-code").val() == "") {
        var errorMessage = requiredText;
        var errorHTML = createAlert(errorMessage);
        document.getElementById("send-cb-notice").innerHTML = errorHTML;
 
        setTimeout(function() {
            closeAlert('send-cb-notice');
        }, 3000); 
        hasPhoneError = false;
        enableDisableButton();
        return;
    }
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: SITE_URL + '/register/submit-active',
        dataType: 'json',
        cache: false,
        data: {
            'phone': $.trim($('#verify-phone').val()),
            'otp_code': $.trim($('#verify-code').val()),
            'carrierCode': $.trim($('#verify-phone').intlTelInput('getSelectedCountryData').dialCode),
        }
    })
    .done(function(response)
    {
        if (response.status) {
            $('#message-verify').show().addClass('alert').html(response.message);
            setTimeout(function() {
                $('#message-verify').hide().removeClass('alert').html('');
                
            }, 3000); 
            hasPhoneError = false;
            enableDisableButton();
        } else {
            console.log(response.status);
            $('#message-verify').show().addClass('success-mess alert success').html(response.message);
            hasPhoneError = false;
            enableDisableButton();
            setTimeout(function() {
                $('#message-verify').hide().removeClass('success-mess alert success').html('');
                window.location.replace(SITE_URL + '/login');
            }, 3000); 
        }
    });
}

function validateInternationalPhoneNumber()
{
    let promiseObj = new Promise(function(resolve, reject)
    {
        let resolveStatus = false;
        if ($.trim($('#phone').val()) !== '') {
            if (!$('#phone').intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($('#phone').val()))) {
                $('#duplicate-phone-error').html('');
                $('#tel-error').addClass('error').html(validPhoneNumberText);
                hasPhoneError = true;
                enableDisableButton();
            } else {
                resolveStatus = true;
                $('#tel-error').html('');
                hasPhoneError = false;
                enableDisableButton();
            }
        } else {
            $('#tel-error').html('');
            hasPhoneError = false;
            enableDisableButton();
        }
        resolve(resolveStatus);
    });  
    return promiseObj;  
}

function validateInternationalPhoneNumberExist()
{
    let promiseObj = new Promise(function(resolve, reject)
    {
        let resolveStatus = false;
        if ($.trim($('#verify-phone').val()) !== '') {
            if (!$('#verify-phone').intlTelInput("isValidNumber") || !isValidPhoneNumber($.trim($('#verify-phone').val()))) {
                $('#duplicate-phone-error').html('');
                $('#tel-error').addClass('error').html(validPhoneNumberText);
                hasPhoneError = true;
                enableDisableButton();
            } else {
                resolveStatus = true;
                $('#tel-error').html('');
                hasPhoneError = false;
                enableDisableButton();
            }
        } else {
            $('#tel-error').html('');
            hasPhoneError = false;
            enableDisableButton();
        }
        resolve(resolveStatus);
    });  
    return promiseObj;  
}

function phoneValidityCheck()
{
    updatePhoneInfo()
    .then(() => 
    {
        validateInternationalPhoneNumber()
        .then((status) => 
        {
            if (status) {
                checkDuplicatePhoneNumber();
            }
        });
    });
}

function phoneExistCheck()
{
    updatePhoneExistInfo()
    .then(() => 
    {
        validateInternationalPhoneNumberExist()
        .then((status) => 
        {
            if (status) {
                checkExistPhoneNumber();
            }
        });
    });
}

$("#phone").on("countrychange", function()
{
    phoneValidityCheck();
});

$("#phone").on('blur', function()
{
    phoneValidityCheck();
});

$("#verify-phone").on("countrychange", function()
{
    phoneExistCheck();
});

$("#verify-phone").on('blur', function()
{
    phoneExistCheck();
});

$(document).ready(function()
{
    $("#email").on('input', function()
    {
        var email = $('#email').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: SITE_URL + '/user-registration-check-email',
            dataType: "json",
            data: {
                'email': email,
            }
        })
        .done(function(response)
        {
            if (response.status) {
                emptyEmail();
                if (validateEmail(email)) {
                    $('#email_error').addClass('custom-error').html(response.fail);
                    $('#email_ok').html('');
                    hasEmailError = true;
                    enableDisableButton();
                } else {
                    $('#email_error').html('');
                }
            } else {
                emptyEmail();
                if (validateEmail(email)) {
                    $('#email_error').html('');
                } else {
                    $('#email_ok').html('');
                }
                hasEmailError = false;
                enableDisableButton();
            }

            /**
             * [validateEmail description]
             * @param  {null} email [regular expression for email pattern]
             * @return {null}
             */
            function validateEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }

            /**
             * [checks whether email value is empty or not]
             * @return {void}
             */
            function emptyEmail() {
                if( email.length === 0 ) {
                    $('#email_error').html('');
                    $('#email_ok').html('');
                }
            }
        });
    });
});
