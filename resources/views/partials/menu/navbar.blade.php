<ul id="user-options" class="dropdown-content">
      <li><a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form></li>
    </ul>
    <ul id="mobile-user-options" class="dropdown-content">
          <li><a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form></li>
        </ul>
<nav>
    <div class="nav-wrapper cyan">
      <a href="http://tfonseca.uk/" class="brand-logo">Enoch</a>
      <ul class="right hide-on-med-and-down">
        <!-- Authentication Links -->
        @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @else
            {{ menu('frontend', 'partials.menu.items') }}
        @endif
        <li><a class="dropdown-trigger" data-beloworigin="true" href="#!" data-target="user-options">
             {{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i>
            </a>
        </li>
      </ul>

    </div>
  </nav>
  <ul class="sidenav" id="mobile-demo">
    @if (Auth::guest())
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
    @else
        {{ menu('frontend', 'partials.menu.items') }}
    @endif
    <li><a class="dropdown-trigger" data-beloworigin="true" href="#!" data-target="mobile-user-options">
         {{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i>
        </a>
    </li>
    <li><a class="sidenav-close" href="#!">Close</a></li>

  </ul>
  <a href="#" data-target="mobile-demo" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
