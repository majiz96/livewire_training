<div class="container">
    <h3 class="my-5">
        {{$editing ? 'ویرایش جایگاه مورد نظر' : 'افزودن جایگاه جدید'}}
    </h3>

    <form class="form-control bg-secondary w-50 text-light my-3" wire:submit="save">

        <div class="row mx-auto align-content-cente my-2">

            <div class="row mx-auto align-content-cente my-2">
                <label for="title"> عنوان جایگاه : </label>
                <input class="mx-auto" type="text" name="title" wire:model.blur="title" autocomplete="off" class="w-auto">
                <div>@error('title') {{$message}} @enderror</div>
            </div>

            <div class="row my-2 px-3">

                <label for="access" class="">سطح دسترسی :</label>
                <div class="col-10">
                <input type="range" id="access" class="mx-auto w-100" wire:model.live="access" step="1" min="0" max="4">
                </div>

                <div class="col-2">
                <p class="mx-auto text-dark my-1" wire:text="access"></p>
                </div>

            </div>

            <div class="row mx-auto">
                <label for="description">توضیحات :</label>
                <textarea id="description" class="mx-auto" wire:model.blur="description"></textarea>
            </div>


            <div class="row my-auto">
                <button type="submit" class="btn btn-sm w-auto btn-primary mx-auto"> {{$editing ? 'ویرایش' : 'افزودن'}} </button>

                @if($editing)
                    <button type="button" class="btn btn-sm w-auto btn-warning mx-auto" wire:click="cancel"> انصراف </button>
                @endif

            </div>
         </form>

        </div>

        <div class="row my-2">

            @if($positions->isNotEmpty())

                <div class="row p-3 my-2 rounded">
                    <div class="col-2 text-center">عنوان</div>
                    <div class="col-2 text-center">سطح دسترسی</div>
                    <div class="col-6 text-center">شرح</div>
                    <div class="col-2 text-center btn-group my-auto"></div>
                </div>

                @foreach($positions as $pos)

                    <div class="row bg-black p-3 my-2 rounded">

                        <div class="col-2 text-center">{{$pos->title}}</div>
                        <div class="col-2 text-center">{{$pos->access}}</div>
                        <div class="col-6 text-center">{{$pos->description}}</div>
                        <div class="col-2 text-center btn-group my-auto">
                        <button class="btn btn-sm w-auto del my-auto" wire:click="question({{$pos->id}})" wire:confirm="">حذف</button>
                        <button class="btn btn-sm w-auto upd my-auto" wire:click="edit({{$pos->id}})">ویرایش</button>
                        </div>

                    </div>
                @endforeach

            @endif

            <div class="my-5"></div>

        </div>

       @if($modalShow == true)
           @include('modalPos');
       @endif



