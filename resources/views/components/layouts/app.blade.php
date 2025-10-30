<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>{{ $title ?? 'Livewire training' }}</title>

        <style>
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .fade-in {
                animation: fadeIn 0.75s ease-in;
            }

            body
            {
                text-align: center;
                direction: rtl;
                font-family: "2  Yekan";
            }
            form
            {
                width: 50%;
                margin: 0 auto;
                padding: 1vh;
                text-align: center;
                border: 0.2vh solid #3f3f3f;
                border-radius: 0.5vh;
            }
            input[type=number]
            {
                width: clamp(100px , 15%, 25%);
            }
            input,textarea
            {
                margin: 1vh 1vw;
                outline: none;
                border: 0.2vh solid #3f3f3f;
                border-radius: 0.5vh;
                padding-inline-start: 2vh;
            }
            textarea
            {
                resize: none;
                height: 5vh;
                width: 90%;
            }

            table
            {
                text-align: center;
                border-radius: 5vh;
                margin: 5vh auto;
            }

            th
            {
                height: 4vh;
                line-height: 4vh;
                color: white;
                padding: 1vh;
            }
            .headbtn
            {
                color: #bc2b89;
                font-weight: bold;
            }.headbtn-selected
             {
                 background: #bc2b89;
                 color: white;
                 font-weight: bold;
             }
            .headbtn:hover
            {
                color: #65214d;
                font-weight: bold;
            }
            td
            {
                padding: 0.5vh;
                border: 0.2vh solid #3f3f3f;
                height: 4vh;
                line-height: 4vh;
            }
            .btn
            {
                width: 10%;
            }
            thead
            {
                border-radius: 0.5vh;
                border-bottom: 0.5vh solid #3f3f3f;

            }
            tbody
            {

            }

            svg
            {
                width: 2.5vw;
            }
            .del
            {
                background: #cc1010;
                color: aliceblue;
            }
            .del:hover
            {
                background: #f60202;
            }
            .upd
            {
                background: #5e00ff;
                color: aliceblue;
            }
            .upd:hover
            {
                background: #ab48ff;
            }
            .nofood
            {
                margin: 5vh auto;
                text-align: center;
                font-size:36px;
                color: red;
            }

            .categoryList
            {
                background: #131313;
            }
            .catName
            {
                text-align: right;

            }
            .cdel,.cupd
            {
                text-decoration: none;
            }
            .cupd:hover
            {
                font-weight: bold;
                color: wheat !important;
            }
            .cdel:hover
            {
                font-weight: bold;
                color: wheat !important;
            }
            .modal-inner img
            {
                width: 128px;
            }
            .oldPrice
            {
                text-decoration:line-through;
                color:red;
            }
            .nav-area
            {
                height: 4vh;
                background: #ab48ff;
            }
            .naviga a
            {
                line-height: 4vh;
                color: aliceblue;
                font-weight: bold;
                text-decoration: none;
            }
            .userform
            {
                height: 8vh;
                line-height: 5vh;
            }
            .userform input
            {
                height: 3vh;
            }
            .errmess
            {
                max-height: 1vh;
                line-height: 1vh;
                color: red;
            }
        </style>
    </head>
    <body class="bg-dark text-light">

        <div class="container-fluid">

    <nav class="nav-area nav mx-auto text-light text-center">

        <div class="naviga mx-auto">
            <a wire:navigate class="nav-item mx-3 my-2" href="/">صفحه اصلی</a>
            @auth()
                <a wire:navigate class="nav-item mx-3 my-2" href="/test">تست </a>
                @can('admin-access')

                    @can('operation-access')
                    <a wire:navigate class="nav-item mx-3 my-2" href="/categories">مدیریت دسته ها</a>
                    @endcan

                    <a wire:navigate class="nav-item mx-3 my-2" href="/foods">مدیریت غذاها</a>

                    @can('full-access')
                    <a wire:navigate class="nav-item mx-3 my-2" href="/positions">جایگاه ها</a>
                    @endcan

                @endcan
            @endauth
            <a wire:navigate class="nav-item mx-3 my-2" href="/users">کاربری</a>
        </div>

    </nav>


        {{ $slot }}
        </div>
    </body>
</html>
