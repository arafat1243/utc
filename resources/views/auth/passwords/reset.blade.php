@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-center">
        <div class="col-md-8 col-12">
            <div class="v-card v-sheet theme--light">
                <div class="v-card__title text-capitalize primary--text">{{ __('Reset Password') }}</div>

                <div class="v-card__text">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="v-input theme--light v-text-field v-text-field--is-booted"
                            style="@error('email') color: rgb(196, 50, 50) !important; @enderror">
                            <div class="v-input__prepend-outer">
                                <div class="v-input__icon v-input__icon--prepend">
                                    <i aria-hidden="true" class="v-icon notranslate mdi mdi-email theme--light"
                                        style="@error('email') color: rgb(196, 50, 50) !important; @enderror"></i>
                                </div>
                            </div>
                            <div class="v-input__control">
                                <div class="v-input__slot">
                                    <div class="v-text-field__slot">
                                        <label for="email" class="v-label theme--light"
                                            style="left: 0px; right: auto; position: absolute; @error('email') color: rgb(196, 50, 50) !important; @enderror">{{ __('E-Mail Address') }}</label>
                                        <input type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                            class="input" id="email">
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
                        <div class="v-input theme--light v-text-field v-text-field--is-booted"
                            style="@error('password') color: rgb(196, 50, 50) !important; @enderror">
                            <div class="v-input__prepend-outer">
                                <div class="v-input__icon v-input__icon--prepend">
                                    <i aria-hidden="true" class="v-icon notranslate mdi mdi-lock theme--light"
                                        style="@error('password') color: rgb(196, 50, 50) !important; @enderror"></i>
                                </div>
                            </div>
                            <div class="v-input__control">
                                <div class="v-input__slot">
                                    <div class="v-text-field__slot">
                                        <label for="password" class="v-label theme--light"
                                            style="left: 0px; right: auto; position: absolute; @error('password') color: rgb(196, 50, 50) !important; @enderror">{{ __('Password') }}</label>
                                        <input type="password" class="input" name="password" required autocomplete="current-password"
                                            id="password">
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
                        <div class="v-input theme--light v-text-field v-text-field--is-booted"
                            style="@error('password') color: rgb(196, 50, 50) !important; @enderror">
                            <div class="v-input__prepend-outer">
                                <div class="v-input__icon v-input__icon--prepend">
                                    <i aria-hidden="true" class="v-icon notranslate mdi mdi-lock theme--light"></i>
                                </div>
                            </div>
                            <div class="v-input__control">
                                <div class="v-input__slot">
                                    <div class="v-text-field__slot">
                                        <label for="password" class="v-label theme--light"
                                            style="left: 0px; right: auto; position: absolute; ">{{ __('Confirm Password') }}</label>
                                        <input type="password" class="input" name="password_confirmation" required autocomplete="current-password"
                                            id="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="v-card__actions">
                            <button type="submit" class="primary mr-4 v-btn v-btn--contained theme--light v-size--default">
                                <span class="v-btn__content">{{ __('Reset Password') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
