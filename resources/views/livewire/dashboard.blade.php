
<div class="container">

    @auth()
    <h1 class="my-4" wire:text="message"></h1>

    <button class="test btn btn-outline-primary" wire:click="messPrint"> test </button>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <button class="test btn btn-outline-light" wire:click="resetMessage"> reset </button>
    <br><hr>

    <h1> {{$user}} </h1>

    <button class="test btn btn-outline-danger" wire:click="logout"
    wire:confirm="آیا قصد خروج از اکانت( {{$user}} ) را دارید؟"> خروج </button>

    @endauth

    @guest()
    <h1 class="my-5"> خوش آمدید </h1>
    @endguest

</div>

