<div class="container my-2">

    <h3>
        {{$editing ? 'ویرایش نوع مورد نظر' : 'افزودن نوع جدید'}}
    </h3>

    <form class="form-control bg-secondary w-50 text-light my-3" wire:submit="save">

        <div class="row mx-auto">

            <div class="col-8">
                <label for="name"> نام دسته : </label>
                <input type="text" name="name" wire:model.blur="name" placeholder="نام دسته" autocomplete="off" class="w-auto">
                <div>@error('name') {{$message}} @enderror</div>
            </div>
            <div class="col-4 my-auto">
                <button type="submit" class="btn btn-sm w-auto btn-primary mx-auto"> {{$editing ? 'ویرایش' : 'افزودن'}} </button>

                @if($editing)
                    <button type="button" class="btn btn-sm w-auto btn-warning mx-auto" wire:click="cancel"> انصراف </button>
                @endif

            </div>
        </div>



    </form>

    <div class="row my-3">

        @foreach($categories as $cat)

            <div class="categoryList col-3 my-2 rounded mx-auto">

                <div class="row">

                    <div class="catName col-6 my-auto"> {{$cat->name}} </div>
                    <div class="col-3"> <button class="btn btn-link w-auto text-danger cdel" wire:click="delete({{$cat->id}})"
                    wire:confirm="آیا از حذف دسته ( {{$cat->name}} ) مطمئن هستید؟">حذف</button> </div>

                    <div class="col-3"> <button class="btn btn-link w-auto text-info cupd" wire:click="edit({{$cat->id}})">ویرایش</button> </div>
                </div>

            </div>
            <div class="col-1"></div>

        @endforeach

    </div>


</div>





