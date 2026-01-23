<li class="nav-item menu-item 
{{ request()->is($link) ? 'active' : '' }}">
    <a href="{{ url($link) }}" class="nav-link">
        <span class="material-symbols-rounded">{{ $icon }}</span>
        <span class="nav-label">{{ $label }}</span>
    </a>


    <li class="nav-item menu-item dropdown-container 
{{ request()->is($link) ? 'active' : '' }}">
    <a href="{{ url($link) }}" class="nav-link dropdown-toggle ">
        <span class="material-symbols-rounded">{{ $icon }}</span>
        <span class="nav-label">{{ $label }}</span>
        <span class="dropdown-icon material-symbols-rounded">{{ $icon2 }}</span>
        
    </a>