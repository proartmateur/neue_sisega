// Validación para que el input acepte letras y números
function validateAlphanumeric(event) {
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    var regex = new RegExp('[a-zA-Z0-9 ñ Ñ]');

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

// Validación para que el input solo acepte letras
function validateText(event) {
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    var regex = new RegExp("[a-zA-Z ñ Ñ À-ÿ]");

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

// Validación para que el input sólo acepte números
function validateNumber(event) {
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    regex = new RegExp("[0-9]+");

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

// Validación para números de teléfono (números, paréntesis, guiones y espacios)
function validatePhoneNumber(event) {
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    regex = new RegExp("[0-9-+() ]+");

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}