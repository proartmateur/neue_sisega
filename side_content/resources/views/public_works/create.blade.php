@extends('layouts.main')

@section('breadcrumb')
    <h3 class="m-subheader__title m-subheader__title--separator">Crear obra</h3>
    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
        <li class="m-nav__item m-nav__item--home">
            <a href="{!!URL::to('/')!!}" class="m-nav__link m-nav__link--icon">
                <i class="m-nav__link-icon la la-home"></i> Inicio
            </a>
        </li>
        <li class="m-nav__separator">-</li>
        <li class="m-nav__item">
            <a href="{!!URL::to('/public-works')!!}" class="m-nav__link">
                <span class="m-nav__link-text">Obras</span>
            </a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Nueva obra
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::open(['route'=>'public-works.store','method'=>'POST','class'=>'m-form m-form--fit m-form--label-align-right']) !!}
            @include('public_works.form')

        {!!Form::close() !!}
    </div>
@endsection

@section('scripts')
    {!! Html::script("vendors/select2/dist/js/i18n/es.js") !!}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.js"></script>--}}
    <script type="text/javascript">
        $(document).ready(function(){
            $('#listMenu').find('.start').removeClass('start');
            $('#liPublic_works').addClass('start');

            /*$("#m_inputmask_7").inputmask("$ 999,999,999.99",{numericInput:!0});*/

            $('.js-example-basic-multiple').select2({
                maximumSelectionLength: 3,
                language: "es"
            });
        });

        $('#supervisors').select2({
            placeholder: "Seleccione residentes",
            ajax: {
                url: "/getSupervisors",
                dataType: 'json',
                delay: 250,
                headers : {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            maximumSelectionLength: 3,
            minimumInputLength: 3,
            language: "es"
        });

        //validacion start formato moneda//
        /*function setSelectionRange(input, selectionStart, selectionEnd) {
            if (input.setSelectionRange) {
                input.focus();
                input.setSelectionRange(selectionStart, selectionEnd);
            } else if (input.createTextRange) {
                var range = input.createTextRange();
                range.collapse(true);
                console.log(collapse);
                range.moveEnd('character', selectionEnd);
                range.moveStart('character', selectionStart);       
                range.select();
            }
        }

        function setCaretToPos(input, pos) {
            setSelectionRange(input, pos, pos);
        }
  
        $("#budget").click(function() {
            var inputLength = $("#budget").val().length;
            setCaretToPos($("#budget")[0], inputLength)
        });

        var options = {
            onKeyPress: function(cep, e, field, options){
                if (cep.length<=6)
                {
                    var inputVal = parseFloat(cep);
                    jQuery('#budget').val(inputVal.toFixed(2));
                }
                                
                var masks = ['#,##0.00', '0.00'];
                mask = (cep == 0) ? masks[1] : masks[0];
                $('#budget').mask(mask, options);
            },
            reverse: true
        };
        $('#budget').mask('#,##0.00', options);*/
        //validacion end formato moneda//

    </script>
@endsection
