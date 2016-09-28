<!-- Sidebar Menu -->
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <li class="{{Helper::setActive('analiza/create')}}"><a href="{{ route('analiza.create') }}">Średnia</a></li>
            <li class="{{Helper::setActive('analiza/lista')}}"><a href="{{ route('analiza.lista') }}">Obszary</a></li>
            <li class="{{Helper::setActive('analiza/lista')}}"><a href="{{ route('analiza.lista') }}">Umiejętności</a></li>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
</aside>