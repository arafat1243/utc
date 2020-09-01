@component('mail::message')
<body>
    <img width="100%" src="{{$user['image']}}">
    <h1 class="title">Hi {{$user['name']}},</h1>
    <p>We see that you have applied for this <strong>{{$user['course']}}</strong> course here. Your course has been approved. Your course will start
    soon. Please visit our institute to get more information about your course.
    </p>
    <p class="note">Note: Login our site to see your course status.</p>
    <h4>
        <samp>E-mail: {{$user['email']}}</samp><br>
    </h4>
</body>
@component('mail::button', ['url' => URL::to('/').'/student','color'=>'success'])
Login hear
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
<style>
    .note{
        font-family: 'Open Sans', sans-serif;
        font-weight: 600;
    }
    .title{
        margin: 20px 0;
        text-transform: capitalize;
    }
</style>