function validateInput(event, id) {
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    var regex;
    switch (id) {
        case 1:
            // Valida que el input solo acepte letras
            regex = new RegExp("[a-zA-Z ñ Ñ À-ÿ]");
            break;
        case 2:
            // Valida que el input solo numeros
            regex = new RegExp("[0-9]+");
            break;
        case 3:
            // Valida que el input para numeros de telefono solo acepte numeros con espacio o guion
            regex = new RegExp("[0-9-+() ]+");
            break;
        case 4:
            regex = new RegExp('[a-zA-Z0-9]');
            break;
    }

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}