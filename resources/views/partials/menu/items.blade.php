   
  
    @foreach($items as $menu_item)
        <li><a href="{{ $menu_item->link() }}">{{ $menu_item->title }}</a></li>
        
    @endforeach
    <li><a class="dropdown-button" href="#!" data-activates="user-options">
         {{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i> 
        </a>
    </li>
