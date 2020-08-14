@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-center">
        <div class="col-md-8 col-12">
            <div class="v-card v-sheet theme--light">
                <div class="v-card__title text-capitalize primary--text">{{ __('Reset Password') }}</div>

                <div class="v-card__text">
                    @if (session('status'))
                        <div role="alert" class="v-alert v-sheet theme--dark" style="background-color: #4caf50 !important;border-color: #4caf50 !important;">
                            <div class="v-alert__wrapper"><i aria-hidden="true"
                                    class="v-icon notranslate v-alert__icon mdi mdi-check-circle theme--dark"></i>
                                <div class="v-alert__content">
                                    {{session('status')}}
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="v-input theme--light v-text-field v-text-field--is-booted">
                            <div class="v-input__prepend-outer">
                                <div class="v-input__icon v-input__icon--prepend">
                                    <i aria-hidden="true" style="@error('email') color: rgb(196, 50, 50) !important; @enderror" class="v-icon notranslate mdi mdi-email theme--light"></i>
                                </div>
                            </div>
                            <div class="v-input__control">
                                <div class="v-input__slot">
                                    <div class="v-text-field__slot">
                                        <label for="email" class="v-label theme--light"
                                            style="left: 0px; right: auto; position: absolute; @error('email') color: rgb(196, 50, 50) !important; @enderror">{{ __('E-Mail Address') }}</label>
                                        <input type="email"  name="email" value="{{ old('email') }}" required autocomplete="email" class="input" autofocus id="email">
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
                        {{-- <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="v-card__actions">
                            <button type="submit" class="primary mr-4 v-btn v-btn--contained theme--light v-size--default">
                                <span class="v-btn__content">{{ __('Send Password Reset Link') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
