<div>

    <h1 wire:text="pageTitle" class="h1 my-5"></h1>

    <nav class="my-2 text-center">

        @if($showSignup)
            <button class="btn mx-5 btn-success" wire:click="showSignupForm">ثبت نام</button>
        @else
            <button class="btn mx-5 btn-outline-success" wire:click="showSignupForm">ثبت نام</button>
        @endif

        @if($showLogin)
            <button class="btn mx-5 btn-primary" wire:click="showLoginForm">ورود</button>
        @else
            <button class="btn mx-5 btn-outline-primary" wire:click="showLoginForm">ورود</button>
        @endif

    </nav>

    <!--  Signup Form  -->
    @if($showSignup)
        <form wire:submit="save" class="form-control bg-dark text-light w-50 px-3 my-5 py-2">

            <div class="row">

                <div class="userform col-4 mx-auto text-center py-1">
                    <label for="name">نام کاربری:</label>
                    <input class="mx-auto" type="text" id="name" wire:model.blur="name" autocomplete="off">
                    <div class="errmess">@error('name') {{$message}} @enderror</div>
                </div>

                <div class="userform col-4 mx-auto text-center py-1">
                    <label for="email">ایمیل:</label>
                    <input class="mx-auto" type="text" id="email" wire:model.blur="email" autocomplete="off">
                    <div class="errmess">@error('email') {{$message}} @enderror</div>
                </div>

                <div class="userform col-4 mx-auto text-center">

                    @can('full-access')
                    <select class="btn btn-outline-secondary w-auto bg-dark" wire:model.blur="position_id">
                        @if($positions->isNotEmpty())
                            <option value=""> انتخاب جایگاه </option>

                            @foreach($positions as $pos)
                                @if(is_int($pos->id))
                                    <option value="{{$pos->id}}">{{ $pos->title }} ({{$pos->access}})</option>
                                @endif
                           @endforeach

                        @endif
                    </select>
                    @endcan

                    <div class="errmess">@error('position_id') {{$message}} @enderror</div>
                </div>

                <div class="userform col-4 mx-auto text-center py-1">
                    <label for="password">رمز عبور:</label>
                    <input class="mx-auto" type="text" id="password" wire:model="password" autocomplete="off">
                    <div class="errmess">@error('password') {{$message}} @enderror</div>
                </div>

                <div class="userform col-4 mx-auto text-center py-1">
                    <label for="repeat">تکرار:</label>
                    <input class="mx-auto" type="text" id="repeat" wire:model="password_confirmation" autocomplete="off">
                    <div class="errmess">@error('password') {{$message}} @enderror</div>
                </div>

                <div class="userform col-4 mx-auto text-center py-1">

                    @if($editing)
                        <button type="submit" class="btn btn-sm btn-success w-auto mx-3">ثبت نام</button>
                        <button type="button" class="btn btn-sm btn-warning w-auto mx-3" wire:click="cancel">انصراف</button>
                    @else
                        <button type="submit" class="btn btn-sm btn-success w-50 mx-3">ثبت نام</button>
                    @endif

                </div>

            </div>
        </form>

        <!-- -----------------------------------------------------Show users------------------------------------------------- -->
    @auth()
        @can('operation-access')
        @if($users->isNotEmpty())

            <div class="row text-center mx-auto my-5 w-50 align-content-center">

                <div class="row px-2 py-3 mx-auto">

                    @can('full-access')
                    <div class="col-1">
                        @if(!empty($selected))
                        <button class="btn btn-sm btn-danger w-auto" wire:click="deleteSelected"
                                wire:confirm=" آیا از حذف کاربران انتخاب شده مطمئن هستید؟"> حذف </button>
                        @else
                            <button class="del btn btn-sm btn-danger w-auto" wire:click="deleteAll"
                                    wire:confirm=" آیا از حذف همه کاربران مطمئن هستید؟"> حذف همه </button>
                        @endif
                    </div>
                    @endcan

                    <div class="col-1">ردیف</div>
                    <div class="col-2">نام کاربری</div>
                    <div class="col-3">ایمیل</div>
                    <div class="col-2">جایگاه</div>

                </div>


                    @foreach($users as $user)
                    <div class="row bg-black rounded px-2 py-3 mx-auto my-2">

                       @can('full-access') <div class="col-1"> <input type="checkbox" wire:model.live="selected" value="{{$user->id}}"> </div>@endcan
                        <div class="col-1">{{ $loop->iteration }}</div>
                        <div class="col-2">{{$user->name}}</div>
                        <div class="col-3">{{$user->email}}</div>

                        <div class="col-2">

                            @foreach($positions as $pos)
                                @if($pos->id == $user->position_id)
                                    {{$pos->title}}
                                @endif
                            @endforeach

                        </div>

                        <div class="col-1"></div>
                        @can('full-access')
                        <div class="col-2 btn-group">
                            <button class="btn btn-sm w-auto del my-auto" wire:click="delete({{$user->id}})"
                                    wire:confirm=" آیا از حذف ({{$user->name}}) مطمئن هستید؟">حذف</button>

                            <button class="btn btn-sm w-auto upd my-auto" wire:click="edit({{$user->id}})">ویرایش</button>
                        </div>
                        @endcan
                        @cannot('full-access')
                            <h5 class="col-2 text-warning"> فاقد مجوز تغییر </h5>
                        @endcannot
                    </div>
                    @endforeach

                </div>



        @else
            <h1 wire:text="empUser" class="text-warning my-5"></h1>
        @endif

    @endif
    @endcan

    @endauth
    <!--  ----------------------------------------------Login Form----------------------------------------------  -->
    @if($showLogin)

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="login" class="form-control bg-dark text-light w-25 p-3">

            <div class="row">

                <div class="userform col-8 mx-auto text-center">
                    <label for="logmail">ایمیل:</label>
                    <input class="mx-auto" type="text" id="logmail" wire:model.blur="logmail" autocomplete="off">
                    <div class="errmess">@error('logmail') {{$message}} @enderror</div>
                </div>

                <div class="userform col-8 mx-auto text-center">
                    <label for="passlog">رمز عبور:</label>
                    <input class="mx-auto" type="text" id="passlog" wire:model.blur="passlog" autocomplete="off">
                    <div class="errmess">@error('passlog') {{$message}} @enderror</div>
                </div>

                <div class="userform row mx-auto text-center">
                    <div class="col-3"></div>
                    <label class="col-3 mx-auto" for="remember">فراموشم نکن</label>
                    <input class="col-1 my-2" type="checkbox" id="remember" wire:model.blur="remember">
                    <div class="col-3"></div>
                </div>

                <div class="userform col-8 mx-auto text-center">
                    <button type="submit" class="btn btn-sm btn-primary w-25 mx-3">ورود</button>
                </div>

            </div>

        </form>
    @endif







</div>
