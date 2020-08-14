@component('mail::message')

<body>
    <img src="{{$imageUrl}}">
    <h1 class="title">Hi {{$name}},</h1>
    <p>Thanks for joining us. Please sign in with your credential and complete your profile.</p>
    <p>Here is your credential.</p>
    <h4>
        <samp>E-mail: {{$email}}</samp><br>
        <samp>Password: {{$password}}</samp>
    </h4>
</body>
@component('mail::button', ['url' => $url,'color' => 'success'])
Complete Your Profile
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
<style>
    .title{
        margin: 20px 0;
    }
</style>