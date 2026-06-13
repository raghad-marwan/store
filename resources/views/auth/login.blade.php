<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | متجر الأجهزة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { background: white; padding: 40px; border-radius: 24px; width: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 30px; color: #1e293b; text-align: center; }
        .input-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
        input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; transition: 0.3s; }
        input:focus { outline: none; border-color: #3b82f6; }
        button { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background: #2563eb; transform: translateY(-2px); }
        .error { background: #fee; color: #c00; padding: 12px; border-radius: 12px; margin-bottom: 20px; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔐 تسجيل الدخول</h2>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="input-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label>
                    <input type="checkbox" name="remember"> تذكرني
                </label>
            </div>
            <button type="submit">دخول</button>
        </form>

        <div class="links">
            <a href="{{ route('register') }}">إنشاء حساب جديد</a> |
            <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
        </div>
    </div>
</body>
</html>
