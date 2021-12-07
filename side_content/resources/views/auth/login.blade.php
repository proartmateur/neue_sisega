@extends('layouts.login_layouts')

<style>
    /* #m_login_signin_submit{
        background-color:0d085a;
        border:0d085a;
    } */
    /* #lateral{
        height: 260px; 
    } */
    


</style>

@section('content')
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
        <div class="m-login__container">
            <div class="m-login__logo" id="lateral">
                <a href="javascript:;">
                    <img alt="" src="../../../assets/app/media/img/logos/sisega_logo.png"  />
                </a>
            </div>
            <div class="m-login__signin">
                <div class="m-login__head">
                    <!-- <h3 class="m-login__title">Sisega</h3> -->
                </div>
                
                <form class="m-login__form m-form" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text"  placeholder="Correo electrónico" name="email" autocomplete="off">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last"  type="password" placeholder="Contraseña" name="password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    {{--<div class="row m-login__form-sub">
                        <div class="col m--align-right m-login__form-right">
                            <a href="{{ url('/password/reset') }}" id="m_login_forget_password" class="m-link">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>--}}
                    <div class="m-login__form-action">
                        <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Iniciar sesión</button>
                    </div>
                </form>
            </div>
            {{--<div class="m-login__forget-password">
                <div class="m-login__head">
                    <h3 class="m-login__title">¿Olvidaste tu contraseña?</h3>
                    <div class="m-login__desc">Escribe tu correo electrónico para reestablecer tu contraseña:</div>
                </div>
                <form class="m-login__form m-form" action="javascript:;">
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text" placeholder="Correo electrónico" name="email" id="m_email" autocomplete="off">
                    </div>
                    <div class="m-login__form-action">
                        <button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primaryr">Reestablecer contraseña</button>&nbsp;&nbsp;
                        <button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">Cancelar</button>
                    </div>
                </form>
            </div>--}}
        </div>
    </div>
@endsection

