<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>لوحة تحكم الأدمن - تسجيل الدخول</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #9775FA, #DE959C);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 380px;
            max-width: 90%;
            text-align: center;
            padding: 2.5rem 2rem;
        }

        .login-container img {
            width: 100px;
            margin-bottom: 1.2rem;
        }

        h2 {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.5rem;
        }

        label {
            display: block;
            text-align: right;
            margin: 1rem 0 0.4rem;
            font-size: 0.95rem;
            color: #444;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #9775FA;
            outline: none;
        }

        button {
            background-color: #9775FA;
            color: white;
            border: none;
            padding: 0.75rem;
            width: 100%;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: bold;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #7e5cf0;
        }

        .error-message {
            background-color: #ffe6e6;
            color: #c00;
            padding: 0.5rem;
            margin-top: 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
       
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Admin Dashboard Icon">

        <h2> Login Admain
             </h2>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <label for="email"> Email</label>
            <input id="email" type="email" name="email" required autofocus />

            <label for="password"> password</label>
            <input id="password" type="password" name="password" required />

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
