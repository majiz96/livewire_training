<!-- Button trigger modal
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
</button>-->

<!-- Modal -->


@if($modalShow == true)

    <div class="modal fixed top-0 left-0 w-100 h-100 d-flex justify-content-center align-items-center bg-opacity-75 bg-dark">


        <div class="row w-25 py-2 bg-dark rounded border border-3 border-danger">

           <p>
               آیا از حذف <b> (( <span class="text-danger">{{ $modalTitle }}</span> )) </b> مطمئن هستید؟
           </p>

            <div aria-describedby="row">
                <button class="btn btn-secondary w-auto m-2" wire:click="$set('modalShow',false)">انصراف</button>
                <button class="btn btn-danger w-auto m-2" wire:click="delete({{$modalId}})">حذف</button>

            </div>

        </div>
    </div>

@endif

