@extends('Emails.Layouts.Master')

@section('message_content')

    <p>Hi there,</p>
    <p>You have received a message from <b>{{$sender_name}}</b> in relation to the event <b>{{$event->title}}</b>.</p>
    <p style="padding: 10px; margin:10px; border: 1px solid #f3f3f3;">
       {!! nl2br($message_content) !!}
    </p>

    <p>
        You can contact <b>{{$sender_name}}</b> directly at <a href='mailto:{{$sender_email}}}'>{{$sender_email}}</a>, or by replying to this email.
    </p>
@stop

@section('footer')
    <br>
    <p style="color:#999;">
        You have received this message as you are listed as the organiser contact on an event which was created with <a href="http://attendize.com/?utm_source=email_footer">Attendize Ticketing</a>.
    </p>
    <br>
    <p style="color:#999;">
        If you have any questions, simply contact us at <a href='mailto:{{config('attendize.incoming_email')}}'>{{config('attendize.incoming_email')}}</a> and we'll be happy to help.
    </p>

@stop
