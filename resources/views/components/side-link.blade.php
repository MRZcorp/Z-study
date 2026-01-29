
<li class="nav-item menu-item 
@isset($drop)dropdown-container @endisset
{{ request()->is($link) ? 'active' : '' }}">
    <a href="{{ url($link) }}" class="nav-link @isset($drop)dropdown-toggle @endisset ">
        <span class="material-symbols-rounded">{{ $icon }}</span>
        <span class="nav-label">{{ $label }}</span>
        @isset($icon2)
        <span class="dropdown-icon material-symbols-rounded">{{ $icon2 }}</span>
        @endisset
    </a>

    
    <ul class="dropdown-menu">
        <li class="nav-item"><a class="nav-link dropdown-title">{{ $label }}</a></li>

        @isset($drop1, $link1,)
        <li class="nav-item"><a href="{{ url($link1) }}" class="nav-link dropdown-link">{{ $drop1 }}</a></li>
        @endisset
        @isset($drop2, $link2)
        <li class="nav-item"><a href="{{ url($link2) }}" class="nav-link dropdown-link">{{ $drop2 }}</a></li>
        @endisset
        @isset($drop3, $link3)
        <li class="nav-item"><a href="{{ url($link3) }}" class="nav-link dropdown-link">{{ $drop3 }}</a></li>
        @endisset
        


       
    </ul>
</li>
