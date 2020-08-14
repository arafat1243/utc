@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-center">
        <div class="col-md-6 col-12">
            <div class="v-card v-sheet theme--light">
                {{-- @if (session('logout'))
                    <div class="v-alert v-sheet theme--dark" style="background: #ff5252" role="alert">
                        <div class="v-alert__wrapper">
                            <i aria-hidden="true" class="v-icon notranslate v-alert__icon mdi mdi-alert theme--dark"></i>
                            <div class="v-alert__content">
                                {{ session('logout') }}
                            </div>
                        </div>
                    </div>
                @endif --}}
            <div class="v-card__title text-capitalize primary--text">{{ __('Login to start session')}}</div>
                    <div class="v-card__text">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="v-input theme--light v-text-field v-text-field--is-booted" style="@error('email') color: rgb(196, 50, 50) !important; @enderror">
                                <div class="v-input__prepend-outer">
                                    <div class="v-input__icon v-input__icon--prepend">
                                        <i aria-hidden="true" class="v-icon notranslate mdi mdi-email theme--light" style="@error('email') color: rgb(196, 50, 50) !important; @enderror"></i>
                                    </div>
                                </div>
                                <div class="v-input__control">
                                    <div class="v-input__slot">
                                        <div class="v-text-field__slot">
                                            <label for="email" class="v-label theme--light"
                                                style="left: 0px; right: auto; position: absolute; @error('email') color: rgb(196, 50, 50) !important; @enderror">E-mail</label>
                                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="input" id="email">
                                        </div>
                                    </div>
                                    @error('email')
                                    <div class="v-text-field__details">
                                        <div class="v-messages thead-light error--text">
                                            <div class="v-messages__wrapper">{{ $message }}</div>
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="v-input theme--light v-text-field v-text-field--is-booted" style="@error('password') color: rgb(196, 50, 50) !important; @enderror">
                                <div class="v-input__prepend-outer">
                                    <div class="v-input__icon v-input__icon--prepend">
                                        <i aria-hidden="true" class="v-icon notranslate mdi mdi-lock theme--light" style="@error('password') color: rgb(196, 50, 50) !important; @enderror"></i>
                                                    </div>
                                                </div>
                                                <div class="v-input__control">
                                                    <div class="v-input__slot">
                                                        <div class="v-text-field__slot">
                                                            <label for="password" class="v-label theme--light" 
                                                                style="left: 0px; right: auto; position: absolute; @error('password') color: rgb(196, 50, 50) !important; @enderror">Password</label>
                                                            <input type="password" class="input" name="password" required autocomplete="current-password" id="password">
                                                        </div>
                                                    </div>
                                                    @error('password')
                                                    <div class="v-text-field__details">
                                                        <div class="v-messages thead-light error--text">
                                                            <div class="v-messages__wrapper">{{ $message }}</div>
                                                        </div>
                                                    </div>
                                                    @enderror
                                                </div>
                                </div>
                                <div class="v-input theme--light v-input--selection-controls v-input--checkbox"
                                                style="max-width: 145px">
                                                <div class="d-flex justify-start align-center">
                                                    <input style="cursor: pointer" type="checkbox" name="remember" id="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <label style="cursor: pointer" class="v-label theme--light ml-2" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="v-card__actions">
                                                <button type="submit"
                                                    class="primary mr-4 v-btn v-btn--contained theme--light v-size--default">
                                                    <span class="v-btn__content">Login</span>
                                                </button>
                                                <div class="spacer"></div>
                                                @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}"
                                                    class="v-btn v-btn--flat v-btn--text theme--light v-size--default primary--text">
                                                    <span class="v-btn__content">Forgot Your Password?</span>
                                                </a>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
@endsection