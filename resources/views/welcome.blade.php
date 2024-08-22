<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="EventTicket est votre plateforme de billetterie électronique pour découvrir et acheter des billets pour vos événements préférés.">
    <title>EventTicket</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #28a745;
            --background-color: #f4f4f4;
            --text-color: #333;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: auto;
            height: 80vh;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .left-section, .right-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .left-section {
            background-color: #4facfe;
            color: #fff;
            flex-direction: column;
            text-align: center;
        }

        .left-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .left-section p {
            font-size: 1.2rem;
            max-width: 80%;
            margin: 0 auto;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .right-section {
            background-color: #fff;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            color: #fff;
            background-color: var(--primary-color);
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-register {
            background-color: var(--secondary-color);
        }

        .btn-register:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
            }

            .left-section, .right-section {
                width: 100%;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .left-section h1 {
                font-size: 2rem;
            }

            .left-section p {
                font-size: 1rem;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    @include('admin.include.partials.message')
    <div class="left-section" aria-label="Section de bienvenue">
        <h1>Bienvenue sur EventTicket!</h1>
        <p id="dynamic-text"></p>
    </div>
    <div class="right-section" aria-label="Section des actions">
        <div class="card">
            <a href="{{ route('login') }}" class="btn" aria-label="Connexion">Connexion</a>
            <a href="{{ route('register') }}" class="btn btn-register" aria-label="Inscription">Inscription</a>
        </div>
    </div>
</div>

<script>
    const text = "Découvrez et achetez des billets pour vos événements préférés en toute simplicité avec notre plateforme de billetterie électronique.";
    let index = 0;
    const speed = 50;
    const delay = 2000;

    function typeWriter() {
        if (index < text.length) {
            document.getElementById("dynamic-text").textContent += text.charAt(index);
            index++;
            setTimeout(typeWriter, speed);
        } else {
            setTimeout(() => {
                document.getElementById("dynamic-text").textContent = "";
                index = 0;
                typeWriter();
            }, delay);
        }
    }

    window.onload = typeWriter;
</script>

</body>
</html>
