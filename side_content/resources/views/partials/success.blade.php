<script>

    var errorsCount = '{{Session::has("success")}}';

    if(errorsCount == 1){

        Swal.fire({
            type: 'success',
            text: '{{Session::get("success")}}',
        });
    }

</script>
