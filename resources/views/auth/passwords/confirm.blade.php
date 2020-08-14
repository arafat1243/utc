@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-center">
        <div class="col-md-8 col-12">
            <div class="v-card v-sheet theme--light">
                <div class="v-card__title text-capitalize primary--text">{{ __('Confirm Password') }}</div>

                <div class="v-card__text">
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="v-input theme--light v-text-field v-text-field--is-booted">
                            <div class="v-input__prepend-outer">
                                <div class="v-input__icon v-input__icon--prepend">
                                    <i aria-hidden="true" class="v-icon notranslate mdi mdi-email theme--light"></i>
                                </div>
                            </div>
                            <div class="v-input__control">
                                <div class="v-input__slot">
                                    <div class="v-text-field__slot">
                                        <label for="password" class="v-label theme--light"
                                            style="left: 0px; right: auto; position: absolute;">{{ __('Password') }}</label>
                                        <input type="password" required autocomplete="current-password" name="password" class="input" id="password">
                                    </div>
                                </div>
                                <div class="v-text-field__details">
                                    <div class="v-messages thead-light">
                                        <div class="v-messages__wrapper"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="v-card__actions">
                                <button type="submit" class="primary mr-4 v-btn v-btn--contained theme--light v-size--default">
                                    <span class="v-btn__content">{{ __('Confirm Password') }}</span>
                                </button>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="v-btn v-btn--flat v-btn--text theme--light v-size--default primary--text">
                                        <span class="v-btn__content">{{ __('Forgot Your Password?') }}</span>
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
