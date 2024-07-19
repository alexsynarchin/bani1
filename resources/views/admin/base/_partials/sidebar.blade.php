<aside class="sidebar">
    <nav class="sidebar-nav">
        <ul class="sidebar-nav__list">
            @if(Auth::user()->id !== 3)
            <li class="sidebar-nav__item">
                <a href="{{route('admin.home')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.home')  ? 'sidebar-nav__link--active' : '' }}">
                    <span class="sidebar-nav__text">Главная</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->id === 2)
            <li class="sidebar-nav__item">
                <a href="{{route('admin.reservation')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.reservation')  ? 'sidebar-nav__link--active' : '' }}">
                    <span class="sidebar-nav__text">Бронирование</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->id === 2 || Auth::user()->id === 3)
                <li class="sidebar-nav__item">
                    <a href="{{route('admin.statistic')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.statistic')  ? 'sidebar-nav__link--active' : '' }}">
                        <span class="sidebar-nav__text">Статистика</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->id !== 3)
            <li class="sidebar-nav__item">
                <a href="{{route('admin.orders')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.orders')  ? 'sidebar-nav__link--active' : '' }}">
                    <span class="sidebar-nav__text">Заказы</span>
                </a>
            </li>
            @endif

            @if(Auth::user()->id === 2)
                <li class="sidebar-nav__item">
                    <a href="{{route('admin.manual')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.manual')  ? 'sidebar-nav__link--active' : '' }}">
                        <span class="sidebar-nav__text">Заказы от админа</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->id === 2)
                <li class="sidebar-nav__item">
                    <a href="{{route('admin.settings')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.settings')  ? 'sidebar-nav__link--active' : '' }}">
                        <span class="sidebar-nav__text">Настройки</span>
                    </a>
                </li>
            @endif

            <li class="sidebar-nav__item">
                <a href="{{route('admin.logout')}}" class="sidebar-nav__link {{ Str::startsWith(Route::current()->getName(),'admin.logout')  ? 'sidebar-nav__link--active' : '' }}">
                    <span class="sidebar-nav__text">Выйти</span>
                </a>
            </li>
        </ul>


    </nav>
</aside>
