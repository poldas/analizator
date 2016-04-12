<!-- Sidebar Menu -->
<ul class="sidebar-menu">
    <li class="header">HEADER</li>
    <!-- Optionally, you can add icons to the links -->
    <li class="{{Helper::setActive('home')}}"><a href="{{ url('home') }}"><i class='fa fa-link'></i> <span>Budżet</span></a></li>
    <li class="{{Helper::setActive('mieszkania')}}"><a href="{{ url('mieszkania') }}"><i class='fa fa-link'></i> <span>Mieszkania</span></a></li>
    <li class="treeview {{Helper::setActive('analiza/*')}}">
        <a href="#"><i class='fa fa-link'></i> <span>Analiza testów</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li class="{{Helper::setActive('analiza/create')}}"><a href="{{ url('analiza/create') }}">Dodaj</a></li>
            <li class="{{Helper::setActive('analiza/przegladaj')}}"><a href="{{ url('analiza/przegladaj') }}">Przeglądaj</a></li>
            <li class="{{Helper::setActive('analiza/wykresy')}}"><a href="{{ url('analiza/wykresy') }}">Wykresy</a></li>
        </ul>
    </li>
</ul><!-- /.sidebar-menu -->