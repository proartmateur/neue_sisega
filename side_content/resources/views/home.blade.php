@extends('layouts.main')

@section('content')
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <br></br>
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-4"></div>
            <div class="col-lg-5">
                <img src="../../../assets/app/media/img//bg/SISEGA-dashboard.png" alt="logos" class="logo-default"  style="">
            </div>
        </div>
    </div>
    
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#home').addClass('start')
        });
    </script>
    </div>
@endsection
