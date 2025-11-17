<x-layouts.app>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-3">تأیید ایمیل</h4>
                <p class="card-text">برای ادامه استفاده از سایت، لطفاً ایمیل خود را تأیید کنید.</p>

                {{-- ⬅️ ۱. استفاده از فرم استاندارد لاراول برای ارسال مجدد --}}
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-auto">
                        ارسال مجدد لینک تأیید
                    </button>
                </form>

                {{-- ⬅️ ۲. نمایش پیام موفقیت تنها در صورت وجود status --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mt-3" role="alert">
                        لینک تأیید ایمیل ارسال شد ✅
                    </div>
                @endif
                <br><br>
                {{-- ⬅️ ۳. افزودن دکمه خروج (با توجه به تعریف روت logout در web.php) --}}
                <form class="d-inline" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger"> خروج </button>
                </form>

            </div>
        </div>
    </div>
</x-layouts.app>
