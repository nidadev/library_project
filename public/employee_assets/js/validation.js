var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var numberRegex = /^[0-9]+$/;
var fileInput = $("#actual-btn")[0];


$(".contactSubmitForm").click(function (event) {
    var innerElements = $('.contactFormClass').find('input, textarea, select');
    var hasValidationErrors = false;

    innerElements.each(function (index, element) {
        var fieldValue = $(element).val();
        var fieldName = $(element).attr('name');


        if (fieldValue.trim() === '') {
            hasValidationErrors = true;
            $(element).addClass('is-invalid');


            return false;
        }

        if (fieldName.toLowerCase().includes('email') && !emailRegex.test(fieldValue)) {
            hasValidationErrors = true;
            $(element).addClass('is-invalid');


            return false;
        }


        if (fieldName.toLowerCase().includes('phone') && !numberRegex.test(fieldValue)) {
            hasValidationErrors = true;
            $(element).addClass('is-invalid');


            return false;
        }


        $(element).removeClass('is-invalid');

    });

    if (hasValidationErrors) {
        event.preventDefault();
    } else {
        $('.contactFormClass').submit();
    }
});
$(".contactHeaderFormClass").click(function (event) {
    var innerElements = $('.contactheaderSubmit').find('input, textarea, select');
    var hasValidationErrors = false;

    innerElements.each(function (index, element) {
        var fieldValue = $(element).val();
        var fieldName = $(element).attr('name');


        if (fieldValue.trim() === '') {
            hasValidationErrors = true;
            $(element).addClass('is-invalid');


            return false;
        }

        if (fieldName.toLowerCase().includes('email') && !emailRegex.test(fieldValue)) {
            hasValidationErrors = true;
            $(element).addClass('is-invalid');


            return false;
        }


        if (fieldName.toLowerCase().includes('phone') && !numberRegex.test(fieldValue)) {
            hasValidationErrors = true;
            $(element).addClass('is-invalid');
            // console.log(fieldName.toLowerCase().includes('phone') && !numberRegex.test(fieldValue))

            return false;
        }

        $(element).removeClass('is-invalid');

    });

    if (hasValidationErrors) {
        event.preventDefault();
    } else {
        $('.contactheaderSubmit').submit();
    }
});
$(".resumeFormButtonSubmition").click(function (event) {
    var innerElements = $(".resumeFormInput").find("input, textarea, select");
    var hasValidationErrors = false;

    innerElements.each(function (index, element) {
        var fieldValue = $(element).val();
        var fieldName = $(element).attr("name");

        if (fieldValue.trim() === "") {
            hasValidationErrors = true;
            $(element).addClass("is-invalid");
            return false;
        }

        if (
            fieldName.toLowerCase().includes("email") &&
            !emailRegex.test(fieldValue)
        ) {
            hasValidationErrors = true;
            $(element).addClass("is-invalid");
            return false;
        }

        if (
            fieldName.toLowerCase().includes("phone") &&
            !numberRegex.test(fieldValue)
        ) {
            hasValidationErrors = true;
            $(element).addClass("is-invalid");
            return false;
        }

        $(element).removeClass("is-invalid");
    });

    // Check if file input has a selected file
    var fileInput = $("#actual-btn")[0];
    var resumeAttachment = $(".resume-attachment");
    if (fileInput.files.length === 0) {
        hasValidationErrors = true;
        resumeAttachment.addClass("is-invalid");
        resumeAttachment.css("border", "1px solid #dc3545");
    } else {
        resumeAttachment.removeClass("is-invalid");
        resumeAttachment.css("border", "none");
    }

    if (hasValidationErrors) {
        event.preventDefault();
    } else {
        $(".resumeFormInput").submit();
    }
});




$('#subscribeBtn').click(function (e) {


    var isValid = true;

    var email = $('#newsletterEmail').val().trim();


    if (email === '' || !emailRegex.test(email)) {
        $('#newsletterEmail').addClass('is-invalid');
        $('#formNewLetterLabel').css('border', '1px solid #dc3545');
        isValid = false;
    } else {
        $('#newsletterEmail').removeClass('is-invalid');
        $('#formNewLetterLabel').css('border', '1px solid #999');
    }

    if (isValid) {
        $('#newsletterForm').submit();
    } else {
        e.preventDefault()
    }
});


$(".contactFormClass input[type='number']").on('keypress', function (e) {
    var forbiddenChars = ['+', '-', '.', 'e', ','];
    var pressedChar = String.fromCharCode(e.which);
    if (forbiddenChars.indexOf(pressedChar) !== -1) {
        e.preventDefault();
    }
});