@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-center">
        <div class="col-md-8 col-12">
            <div class="v-card v-sheet theme--light">
                <div class="v-card__title text-capitalize primary--text">{{ __('Verify Your Email Address') }}</div>

                <div class="v-card__text">
                    @if (session('resent'))
                        <div class="v-alert v-sheet theme--dark primary " role="alert">
                            <div class="v-alert__wrapper">
                                <i aria-hidden="true" class="v-icon notranslate v-alert__icon mdi mdi-information theme--dark"></i>
                                <div class="v-alert__content">{{ __('A fresh verification link has been sent to your email address.') }}</div>
                            </div> 
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="primary mr-4 v-btn v-btn--contained theme--light v-size--default">
                            <span class="v-btn__content">{{ __('click here to request another') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
