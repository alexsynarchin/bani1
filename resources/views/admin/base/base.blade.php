<!DOCTYPE html>
<html lang="ru">
@include('admin.base._partials.head')
<body >
<div class="page-wrapper"  id="app">
    @auth()
        @include('admin.base._partials.header')
    @endauth
        @auth()
        <div class="dashboard-main">

                @include('admin.base._partials.sidebar')
                <main class="dashboard-content">
                   @yield('content')
                </main>
        </div>
        @endauth
        @guest()
            @yield('content')
        @endguest
    @auth()
        @include('admin.base._partials.footer')
    @endauth

    <div class="overlay" v-if="isLoading">
        <div class="overlay__inner">
            <div class="overlay__content"><span class="spinner"></span></div>
        </div>
    </div>
</div>

@include('admin.base._partials.scripts')
@yield('scripts')

</body>
</html>
