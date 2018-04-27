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
<nav>
    <div class="nav-wrapper">
      <a href="http://maps-tmsfonseca660193.codeanyapp.com/" class="brand-logo">Logo</a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-med-and-down">
        <!-- Authentication Links -->
        @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @else
            {{ menu('frontend', 'partials.menu.items') }}
        @endif        
      </ul>
      <ul class="side-nav" id="mobile-demo">
        @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @else
            {{ menu('frontend', 'partials.menu.items') }}
        @endif
    
      </ul>
    </div>
  </nav>