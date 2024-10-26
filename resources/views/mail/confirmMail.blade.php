@extends('layouts.mail')
@section('content')
    <h1>Ciao {{ $userName }},</h1>
    <p>Grazie per esserti registrato! Per favore conferma il tuo indirizzo email cliccando sul link qui sotto:</p>
    <a href="{{ $verificationUrl }}">Conferma Email</a>
    <p>Grazie!</p>
@endsection
