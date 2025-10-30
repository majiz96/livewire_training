
<div class="row my-5">

    <h1>
        {{$editing ? 'ویرایش غذای مورد نظر' : 'افزودن به لیست غذا'}}
    </h1>


    @can('edit-access')
    <form class="form-control bg-secondary w-auto text-light my-2" wire:submit="save">

        <div class="row mx-auto">


            <div class="col-4 mx-auto">
                <label for="name"> نام غذا : </label>
                <input type="text" name="name" wire:model.blur="name" placeholder="نام غذا" autocomplete="off">
                <div>@error('name') {{$message}} @enderror</div>
            </div>

            <div class="col-3 mx-auto">
                <label for="price"> قیمت : </label>
                <input type="number" name="price" min="1" wire:model.blur="price" placeholder="0">
                <div>@error('price') {{$message}} @enderror</div>
            </div>

            <select name="cats" id="cats" class="col-2 bg-dark rounded mx-auto h-auto text-light" wire:model.blur="cat_id">
                <option value="" class="bg-dark">نوع غذا</option>
                @foreach($categories as $cat)
                    <option value="{{$cat->id}}" class="bg-dark">{{$cat->name}}</option>
                @endforeach
            </select>

            <div>@error('cat_id') {{$message}} @enderror</div>


            <div class="mx-auto">
                <textarea wire:model.blur="description" placeholder="محتویات"></textarea>
                <div>@error('description') {{$message}} @enderror</div>
            </div>

            <div class="col-4 mx-auto">
                <div class="row">

                    <div class="col-4">
                        <input type="file" id="image" wire:model.blur="image" class="d-none" >
                        <label for="image" class="btn btn-sm btn-danger w-auto my-2"> انتخاب عکس</label>
                    </div>

                    @if ($image)

                        @if(is_string($image) && $editing)
                            <div class="col-8">
                                {{--                                <span>پیش‌نمایش:</span>--}}
                                <img src="{{$image}}" class="img-fluid rounded" width="50px">
                                <br>
                                @error('image') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        @else

                            <div class="col-8">
                                {{--                         <span>پیش‌نمایش:</span>--}}
                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" width="50px">
                                <br>
                                @error('image') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    @endif
                </div>

            </div>

            @can('operation-access')
            <button type="submit" class="btn btn-sm btn-primary h-25 mx-auto"> {{$editing ? 'ویرایش' : 'افزودن'}} </button>
            @endcan
            @cannot('operation-access')
                @if($editing)
                    <button type="submit" class="btn btn-sm btn-primary h-25 mx-auto"> ویرایش </button>
                @else
                    <button type="submit" class="btn btn-sm btn-dark disabled h-25 mx-auto" disabled> ویرایش </button>
                @endif
            @endcannot
        </div>




        @if($editing)
            <button wire:click="cancel" class="btn btn-sm btn-warning mx-auto my-2"> انصراف </button>
        @endif

    </form>
    @endcan



    <div class="selectBox rounded">
        @if($categories -> isNotEmpty())


            @foreach($categories as $cat)
                &nbsp;
                <input type="checkbox" class="btn-check" id="btn-check-{{$cat->id}}-outlined" autocomplete="off"
                       wire:model.live="selectedCategories" value="{{$cat->id}}" checked>

                <label class="btn btn-outline-warning" for="btn-check-{{$cat->id}}-outlined">{{$cat->name}}</label>
                &nbsp;

            @endforeach

            @if(!empty($selectedCategories) && count($selectedCategories) !== $categories->count())

                <button class="btn btn-outline-info" wire:click="catReset"> همه </button>

            @else

                <button class="btn btn-info" wire:click="catReset"> همه </button>

            @endif

        @endif
    </div>

    <div>
        @if($foods->isNotEmpty())

            <table class="table table-responsive-sm table-dark table-striped w-auto">

                <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>تصویر</th>
                    <th>
                        @if($sortColumn == 'name')
                            <input type="button" class="headbtn-selected btn btn-sm w-auto" wire:click="sortColUpdate('name')" id="name" value="نام">
                        @else
                            <input type="button" class="headbtn btn btn-sm w-auto" wire:click="sortColUpdate('name')" id="name" value="نام">
                        @endif


                        @if($sortDirection === 'asc')
                            <button class="btn btn-sm btn-link" wire:click="sortDirUpdate('desc')">▲</button>
                        @else
                            <button class="btn btn-sm btn-link" wire:click="sortDirUpdate('asc')">▼</button>
                        @endif
                    </th>

                    <th>محتویات</th>

                    <th>

                        @if($sortColumn == 'price')
                            <input type="button" class="headbtn-selected btn btn-sm w-auto" wire:click="sortColUpdate('price')" id="price" value="قیمت(تومان)">
                        @else
                            <input type="button" class="headbtn btn btn-sm w-auto" wire:click="sortColUpdate('price')" id="price" value="قیمت(تومان)">
                        @endif

                        @if($sortDirection === 'asc')
                            <button class="btn btn-sm btn-link" wire:click="sortDirUpdate('desc')">▲</button>
                        @else
                            <button class="btn btn-sm btn-link" wire:click="sortDirUpdate('asc')">▼</button>
                        @endif
                    </th>

                    <th>قیمت نهایی</th>
                    @can('edit-access')
                    <th>تغییر</th>
                    @endcan

                </tr>

                </thead>


                <tbody class="text-center">

                @foreach($foods as $food)

                    <tr wire:key="{{$food->id}}"
                        class="{{ $highlightedId === $food->id ? 'fade-in' : '' }}">

                        <td class="my-auto">{{ $foods->firstItem() + $loop->index }}</td>

                        <td><img src="{{$food->image}}" alt="" width="40px"></td>

                        <td class="my-auto">{{$food->name}}</td>

                        <td class="my-auto">{{$food->description}}</td>

                        <td class="my-auto">

                            @if($food->discount)

                                <span class="oldPrice"> {{ number_format($food->price) }} </span>

                            @else
                                {{ number_format($food->price) }}
                                &nbsp;
                                <button type="button" class="btn btn-sm btn-primary w-auto" wire:click="activeDiscount({{$food->id}})"> تخفیف </button>
                            @endif
                        </td>


                        <td class="my-auto px-4">
                            @if($food->discount)

                                {{number_format($food->price - ($food->price * $food->discount/100) )  }}

                                <button type="button" class="btn btn-sm btn-warning position-relative w-auto" wire:click="activeDiscount({{$food->id}})">
                                    تغییر تخفیف
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                  %{{$food->discount}}
                                  <span class="visually-hidden"></span>
                                </span>
                                </button>
                            @else
                                {{ number_format($food->price) }}
                            @endif
                        </td>

                        @can('edit-access')
                        <td class="my-auto">
                            <div class="btn-group my-auto">
                                <button class="btn btn-sm w-auto del my-auto" wire:click="question({{$food->id}})">حذف</button>
                                <button class="btn btn-sm w-auto upd my-auto" wire:click="edit({{$food->id}})">ویرایش</button>
                            </div>
                        </td>
                        @endcan

                    </tr>
                    @include('modal')
                    @include('discount')
                @endforeach



                </tbody>
            </table>

            {{ $foods->links(data: ['scrollTo' => false]) }}

        @else
            <h1 class="my-5"> غذایی در سیستم ثبت نشده است </h1>
        @endif
    </div>





