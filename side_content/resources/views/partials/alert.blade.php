<script>

    var errorsCount = '{{Session::has("error")}}';
    if(errorsCount == 1){
        Swal.fire({
            type: 'error',
            text: '{{Session::get("error")}}',
        });
    }

</script>
