<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور | متجر الأجهزة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .box { background: white; padding: 40px; border-radius: 24px; width: 420px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 25px; color: #1e293b; text-align: center; }
        .input-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: #334155; }
        input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 16px; }
        input:focus { outline: none; border-color: #ec4899; }
        button { width: 100%; padding: 14px; background: #ec4899; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; }
        button:hover { background: #db2777; }
        .alert { padding: 12px; border-radius: 12px; margin-bottom: 20px; }
        .alert-danger { background: #fee; color: #c00; }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔄 إعادة تعيين كلمة المرور</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="input-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required>
            </div>
            <div class="input-group">
                <label>كلمة المرور الجديدة</label>
                <input type="password" name="password" required>
            </div>
            <div class="input-group">
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit">حفظ كلمة المرور الجديدة</button>
        </form>
    </div>
</body>
</html>
