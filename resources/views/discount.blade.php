<!-- Button trigger modal
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
</button>-->

<!-- Modal -->


@if($discShow == true)

    <div class="modal fixed top-0 left-0 w-100 h-100 d-flex justify-content-center align-items-center bg-opacity-75 bg-dark">


        <div class="modal-inner row w-auto py-2 bg-dark rounded border border-3 border-primary text-center">


        <img src="{{$modalImg}}" width="2px" class="img-fluid rounded m-2 mx-auto"><br><br>


                <h3 class="text-primary"> {{$modalName}} </h3>

            <br>
           <p>
              {{number_format($modalPrice - ($modalPrice * $discount/100) )}}
           </p>


            <div class="row">
            <input type="range" wire:model.live="discount" step="2" min="0" max="40">
                <p class="mx-auto text-primary">{{$discount}} %</p>
            </div>

            <div  class="row">
                <button class="btn btn-secondary w-auto m-2" wire:click="$set('discShow',false)">انصراف</button>
                <button class="btn btn-primary w-auto m-2" wire:click="setDiscount({{$modalId}})">ثبت تخفیف</button>
            </div>

        </div>
    </div>

@endif

