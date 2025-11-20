<?php

namespace App\Livewire;


use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
//use phpDocumentor\Reflection\Types\Integer;
use App\Models\User;
use App\Models\Position;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Scalar\Int_;

class Users extends Component
{
    use WithPagination;
    public $pageTitle = "صفحه ورود و ثبت نام کاربران";
    public $empUser = "کاربری ثبت نام نکرده است";
    public $showSignup = true;
    public $showLogin = false;

    #[Validate("required",message : "نام کاربری الزامی است")]
    #[Validate("min:3",message: "نام کاربری باید حداقل ۳ کلمه باشد")]
    #[Validate("string",message: "ساختار متنی نام کاربری معتبر نیست")]
    #[Validate("unique:users,name", message: "این نام کاربری قبلا استفاده شده است")]
    public string $name;

    #[Validate("required",message : "نام کاربری الزامی است")]
    #[Validate("min:3",message: "نام کاربری باید حداقل ۳ کلمه باشد")]
    #[Validate("string",message: "ساختار متنی نام کاربری معتبر نیست")]
    public string $u_name;

    #[Validate("required",message : "ایمیل الزامی است")]
    #[Validate("email:rfc,dns",message: "ایمیل معتبر نیست")]
    #[Validate("unique:users,email", message: "این ایمیل قبلا استفاده شده است")]
    public string $email;

    #[Validate("required",message : "ایمیل الزامی است")]
    #[Validate("email:rfc,dns",message: "ایمیل معتبر نیست")]
    public string $u_email;

    #[Validate("required",message : "رمز عبور الزامی است")]
    #[Validate("min:8",message: "حداقل باید ۸ حرف باشد")]
    #[Validate("confirmed",message: "رمز عبور درست تکرار نشده است")]
    public string $password;
    public string $password_confirmation;
    #[Validate("required",message : "رمز عبور الزامی است")]


    #[Validate("required",message : "جایگاه کاربر الزامی است")]
    #[Validate("integer",message : "نوع داده اشتباه است")]
    public Int $position_id = 6;

    public $logmail;

    public $passlog;

    public $remember;

    public $editing = null;

    public $selected = [];

    public $selectAll = false;

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

        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'position_id' => 'required|integer',
            'password' => 'required|confirmed|min:8',
        ]);

        $data = $this->pull(['name', 'email', 'position_id', 'password', 'password_confirmation']);
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
            //return redirect()->intended('/');
            $user->sendEmailVerificationNotification();
            return redirect(route('verification.notice'));
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

        if (Auth::attempt(['email' => $this->logmail, 'password' => $this->passlog],$this->remember)) {

            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {
                return redirect(route('verification.notice'));
            }

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

   public function deleteSelected()
   {
       if (Gate::denies('full-access'))
       {
       abort(403,'برای حذف کاربران به دسترسی کامل نیاز دارید');
       }
       User::whereIn('id',$this->selected)->delete();
   }
   public function updatedSelectAll($value)
   {
       if($value)
       {
           $maxAccess = Position::max('access');

           $this->selected = User::join('positions', 'users.position_id', '=', 'positions.id')
               ->where('positions.access', '<', $maxAccess)
               ->pluck('users.id')
               ->toArray();
       }
       else
       {
           $this->selected = [];
       }
   }
    public function render()
    {
        return view('livewire.users',['users'=>User::all(),'positions'=>Position::orderBy('access','asc')->get()]);
    }
}
