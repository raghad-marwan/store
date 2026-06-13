<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نسيت كلمة المرور | متجر الأجهزة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .box { background: white; padding: 40px; border-radius: 24px; width: 420px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 10px; color: #1e293b; text-align: center; }
        p { color: #64748b; text-align: center; margin-bottom: 25px; font-size: 14px; }
        .input-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
        input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; }
        input:focus { outline: none; border-color: #3b82f6; }
        button { width: 100%; padding: 14px; background: #3b82f6; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background: #2563eb; }
        .alert { padding: 12px; border-radius: 12px; margin-bottom: 20px; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger { background: #fee; color: #c00; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #3b82f6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔑 نسيت كلمة المرور</h2>
        <p>أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور</p>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit">إرسال رابط إعادة التعيين</button>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
        </div>
    </div>
</body>
</html>
