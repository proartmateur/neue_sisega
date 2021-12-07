
@if(count($errors)>0)
    <div style="position: absolute;
    z-index: -10;">
        <ul class="alertList" style="list-style: none;text-align: center;">
            <?php $count = 1;?>
            @foreach($errors->all() as $error)
                <li>{{$count++}} - {!!   $error!!}</li>
            @endforeach
        </ul>
    </div>
@endif


<script>

    var errorsCount = '{{count($errors)}}';
    if(errorsCount > 0){

        var listErrors = $('.alertList').get(0);

        Swal.fire({
            type: 'warning',
            title: 'Oops...',
            html: listErrors
        });
    }

</script>

