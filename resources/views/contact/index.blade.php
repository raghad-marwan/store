@extends('layouts.app')

@section('title', 'اتصل بنا - متجر الأجهزة')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 30px;">📞 اتصل بنا</h1>

    {{-- Toast --}}
    @if(session('success'))
    <div id="toast" style="position: fixed; bottom: 30px; left: 30px; background: #10b981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 12px; font-weight: 500; z-index: 9999; opacity: 1;">
        <i class="fas fa-check-circle" style="font-size: 22px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    <script>setTimeout(function(){ var t=document.getElementById('toast'); if(t){ t.style.opacity='0'; t.style.transform='translateY(100px)'; } }, 4000);</script>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

        {{-- فورم التواصل --}}
        <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px;">💬 أرسل رسالة</h3>

            @if($errors->any())
                <div style="background: #fee; color: #c00; padding: 12px; border-radius: 10px; margin-bottom: 15px;">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <input type="text" name="name" placeholder="الاسم الكامل *" value="{{ old('name') }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <input type="email" name="email" placeholder="البريد الإلكتروني *" value="{{ old('email') }}" required
                           style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <textarea name="message" rows="4" placeholder="رسالتك *" required
                              style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 10px;">{{ old('message') }}</textarea>
                </div>
                <button type="submit" style="background: #3b82f6; color: white; padding: 14px 30px; border: none; border-radius: 12px; cursor: pointer; font-weight: bold; font-size: 16px; width: 100%;">
                    <i class="fas fa-paper-plane"></i> إرسال الرسالة
                </button>
            </form>
        </div>

        {{-- معلومات التواصل --}}
        <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 20px;">📋 معلومات التواصل</h3>

            <div style="margin-bottom: 25px;">
                <i class="fas fa-phone" style="color: #3b82f6; font-size: 20px;"></i>
                <p style="font-weight: bold; margin-top: 5px;">الهاتف</p>
                <p style="color: #64748b;">+970 592 123 456</p>
            </div>
            <div style="margin-bottom: 25px;">
                <i class="fas fa-envelope" style="color: #3b82f6; font-size: 20px;"></i>
                <p style="font-weight: bold; margin-top: 5px;">البريد الإلكتروني</p>
                <p style="color: #64748b;">info@techstore.com</p>
            </div>
            <div style="margin-bottom: 25px;">
                <i class="fas fa-map-marker-alt" style="color: #3b82f6; font-size: 20px;"></i>
                <p style="font-weight: bold; margin-top: 5px;">العنوان</p>
                <p style="color: #64748b;">فلسطين - غزة</p>
            </div>
            <div>
                <i class="fas fa-clock" style="color: #3b82f6; font-size: 20px;"></i>
                <p style="font-weight: bold; margin-top: 5px;">ساعات العمل</p>
                <p style="color: #64748b;">السبت - الخميس: 9 صباحاً - 6 مساءً</p>
            </div>
        </div>
    </div>
</div>
@endsection
