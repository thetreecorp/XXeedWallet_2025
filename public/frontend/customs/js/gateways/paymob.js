"use strict";

function restrictNumberToPrefdecimalOnInput(e) {
    var type = $('select#currency_id').find(':selected').data('type')
    restrictNumberToPrefdecimal(e, type);
}

function determineDecimalPoint() {
    var currencyType = $('select#currency_id').find(':selected').data('type')
    if (currencyType == 'crypto') {
        $('.pFees, .fFees, .total_fees').text(CRYPTODP);
        $("#amount").attr('placeholder', CRYPTODP);
    } else if (currencyType == 'fiat') {
        $('.pFees, .fFees, .total_fees').text(FIATDP);
        $("#amount").attr('placeholder', FIATDP);
    }
}

// external.js file
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    return (charCode > 31 && (charCode < 48 || charCode > 57)) ? false : true;
}

if ($('.container-fluid').find('#depositPaymob').length) {
    var payment_buttons = "";
    var btnClasses = ['btn btn-lg btn-primary mt-4', 'btn btn-lg btn-success mt-4', 'btn btn-lg btn-warning mt-4'];
    types.forEach(function (type, ind){
        payment_buttons += "<div class=\"d-grid\"><a href='"+redirect_url+type+"' class='"+btnClasses[ind]+"'> Pay with "+type+" </a></div>";
    })
    $("#paymob-button-container").html(payment_buttons);
}
if ($('.container-fluid').find('#depositConfirm').length) {
    $('#depositConfirmForm').on('submit', function () {
        $('#depositConfirmBtn').attr("disabled", true);
        $('#depositConfirmBackBtn').removeAttr('href');
        $(".spinner").removeClass('d-none');
        $("#rightAngleSvgIcon").addClass('d-none');
        $('#depositConfirmBtnText').text(confirmBtnText);
    });
}
if ($('.container-fluid').find('#depositSuccess').length) {
    $(document).ready(function () {
        // disable browser back button
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };

        // disable F5
        $(document).on("keydown", function (e) {
            if ((e.which || e.keyCode) == 116) {
                e.preventDefault();
            }
        });

        // disable Ctrl+R
        $(document).on("keydown", function (e) {
            if (e.keyCode == 82 && e.ctrlKey) {
                e.preventDefault();
            }
        });
    });
}
