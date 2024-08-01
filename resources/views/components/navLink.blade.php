{{-- Komponen NavLink --}}
@props(['href', 'iconClass', 'label', 'children' => null])

<li class="nav-item">
    <a href="{{ $href }}" class="nav-link {{ request()->is($href) ? 'active' : '' }}">
        <i class="nav-icon {{ $iconClass }}"></i>
        <p>
            {{ $label }}
            @if($children)
                <i class="nav-arrow bi bi-chevron-right"></i>
            @endif
        </p>
    </a>
    @if($children)
        <ul class="nav nav-treeview">
            @foreach($children as $child)
                <li class="nav-item">
                    <a href="{{ $child['href'] }}" class="nav-link {{ request()->is($child['href']) ? 'active' : '' }}">
                        <i class="nav-icon {{ $child['iconClass'] ?? 'bi bi-circle' }}"></i>
                        <p>{{ $child['label'] }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</li>