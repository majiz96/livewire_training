<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

//use phpDocumentor\Reflection\Types\Integer;
use App\Models\User;
use App\Models\Position;
use PhpParser\Node\Scalar\Int_;

class Users extends Component
{
    public $pageTitle = "صفحه ورود و ثبت نام کاربران";
    public $empUser = "کاربری ثبت نام نکرده است";
    public $showSignup = false;
    public $showLogin = true;

    #[Validate("required",message : "نام کاربری الزامی است")]
    #[Validate("min:3",message: "نام کاربری باید حداقل ۳ کلمه باشد")]
    #[Validate("string",message: "ساختار متنی نام کاربری معتبر نیست")]
    #[Validate("unique:users,name", message: "این نام کاربری قبلا استفاده شده است")]
    public string $name;

    #[Validate("required",message : "ایمیل الزامی است")]
    #[Validate("email:rfc,dns",message: "ایمیل معتبر نیست")]
    #[Validate("unique:users,email", message: "این ایمیل قبلا استفاده شده است")]
    public string $email;

    #[Validate("required",message : "رمز عبور الزامی است")]
    #[Validate("min:8",message: "حداقل باید ۸ حرف باشد")]
    #[Validate("confirmed",message: "رمز عبور درست تکرار نشده است")]
    public string $password;
    public string $password_confirmation;
    #[Validate("required",message : "رمز عبور الزامی است")]


    #[Validate("required",message : "جایگاه کاربر الزامی است")]
    #[Validate("integer",message : "نوع داده اشتباه است")]
    public Int $position_id = 4;

    public $logmail;

    public $passlog;

    public $editing = null;


    public function showLoginForm()
    {
    $this->showSignup = false;
    $this->showLogin = true;
    }
    public function showSignupForm()
    {
    $this->showLogin = false;
    $this->showSignup = true;
    }

    public function edit($id)
    {
        if (Gate::denies('full-access')) {
            abort(403,'برای ویرایش کاربران به دسترسی کامل نیاز دارید');
        }

        $user = User::findOrFail($id);
        $this->editing = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->position_id = $user->position_id;
        $this->password = "";

    }
    public function cancel()
    {
        $this->editing = null;
        $this->reset();
    }

    public function save()
    {

        $this->showSignup = true;
        $this->showLogin = false;

    if ($this->editing) {
        $this->validate();
        $data = $this->pull();
        $user = User::find($this->editing)->update($data);
        $this->editing = null;
    }
    else
    {
        $this->validate();
        $data = $this->pull();
        $user = User::create($data);

        if(empty(Auth::user()))
        {
            Auth::login($user);
            return redirect()->intended('/');
        }

    }
    $this->reset();

    }

    public function login()
    {
        $this->validate([
            "logmail" => "required|email:rfc,dns",
            "passlog" => "required",
        ],
        [
            "logmail.required" => "ایمیل الزامی است.",
            "logmail.email" =>  "ایمیل معتبر نمی باشد.",
            "passlog.required" => "رمز عبور الزامی است."
        ]);
        $this->showLogin = false;
        $this->showSignup = true;

        if (Auth::attempt(['email' => $this->logmail, 'password' => $this->passlog])) {
            session()->flash('message', 'ورود موفقیت‌آمیز بود!');
            session()->regenerate();
            return redirect()->intended('/');
        }
        else
        {
           $this->addError('email','رمز عبور یا ایمیل اشتباه وارد شده است');
        }
    }

    public function delete($id)
    {
        if (Gate::denies('full-access')) {
            abort(403,'برای حذف کاربران به دسترسی کامل نیاز دارید');
        }

    $user=User::findOrFail($id);
    $user->delete();
    }

    public function render()
    {
        return view('livewire.users',['users'=>User::all(),'positions'=>Position::orderBy('access','asc')->get()]);
    }
}
