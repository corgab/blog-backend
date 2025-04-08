<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.8;
            margin: 0;
            padding: 0;
            background-color: #F8F5F1;
            color: #181618;
        }

        .container {
            max-width: 600px;
            margin: 0px auto;
            padding: 30px;
            background-color: #FFFFFF;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .header {
            background-color: #FFCF01;
            color: #FFFFFF;
            padding: 40px;
            border-radius: 15px 15px 0 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        h1 {
            color: #FFCF01;
            font-size: 30px;
            margin-top: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .post {
            background: #FFFBE8;
            padding: 20px;
            margin: 20px 0;
            border-radius: 12px;
            text-align: left;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
        }

        .post h2 {
            font-size: 22px;
            color: #181618;
            margin-bottom: 10px;
        }

        .post p {
            font-size: 15px;
            color: #616670;
        }

        a.button {
            display: inline-block;
            background-color: #FFCF01;
            color: #FFFFFF;
            padding: 8px 13px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
        }

        a.button:hover {
            background-color: #A48916;
            transform: scale(1.05);
        }

        .unsubscribe {
            margin-top: 25px;
            font-size: 14px;
        }

        .unsubscribe a {
            color: #ff4d4d;
            text-decoration: underline;
            font-weight: bold;
        }

        .footer {
            background-color: #24231D;
            color: #FFFFFF;
            padding: 25px;
            margin-top: 40px;
            border-radius: 0 0 15px 15px;
            font-size: 14px;
            text-align: center;
        }

        .footer p {
            margin: 0;
            opacity: 0.8;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            display: inline-block;
            margin: 0 10px;
        }

        .social-icons img {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img class="logo" src={{ url('/storage/images/logo-white.svg') }} alt="Logo del blog">
            <div>La tua {{ config('app.name') }} Newsletter️️</div>
        </div>
        <h1>Ciao {{ $user }}</h1>
        <p>Siamo felici di ritrovarti! Ecco le ultime novità:</p>

        @foreach ($posts as $post)
            <div class="post">
                <h2>{{ $post->title }}</h2>
                <p>{!! Str::limit($post->description, 300) !!}</p>
                <a class="button" href="{{ config('app.frontend_url') . '/' . $post->slug }}">Continua a leggere</a>
            </div>
        @endforeach

        <p class="unsubscribe">
            Se non vuoi più ricevere la newsletter,
            <a href={{ config('app.frontend_url') . '/' }}>cancellati qui</a>.
        </p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
        {{-- <div class="social-icons">
            <a href="SOCIAL_FACEBOOK_URL"><img src="facebook-icon.svg" alt="Facebook"></a>
            <a href="SOCIAL_TWITTER_URL"><img src="twitter-icon.svg" alt="Twitter"></a>
            <a href="SOCIAL_INSTAGRAM_URL"><img src="instagram-icon.svg" alt="Instagram"></a>
        </div> --}}
    </div>
</body>

</html>
