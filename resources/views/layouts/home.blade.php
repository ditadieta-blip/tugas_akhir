<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSC - Sistem Informasi Senam</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f6ff;
            color: #333;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(90deg, #1e3a8a, #2563eb);
            padding: 18px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: 0.3s ease-in-out;
        }

        header.scrolled {
            padding: 14px 8%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            backdrop-filter: blur(8px);
        }

        header h1 {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 30px;
            font-weight: 500;
            transition: 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .nav-login {
            background: white;
            color: #1e3a8a !important;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
        }

        footer {
            background: #1e3a8a;
            color: white;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

<header id="navbar">
    <h1>BSC</h1>
    <nav>
        <a href="#home">Home</a>
        <a href="#program">Program</a>
        <a href="{{ route('login') }}" class="nav-login">Login</a>
    </nav>
</header>

@yield('content')

<footer>
    <p>© 2026 BSC - Sistem Informasi Senam</p>
</footer>

<script>
    window.addEventListener("scroll", function() {
        const navbar = document.getElementById("navbar");
        navbar.classList.toggle("scrolled", window.scrollY > 50);
    });
</script>

</body>
</html>
