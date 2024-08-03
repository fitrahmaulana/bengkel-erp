{{-- Komponen nav-link --}}
@props(['href', 'iconClass', 'label', 'children' => null, 'routeName' => null])

@php
    $isActive = $routeName ? request()->routeIs($routeName) : false;
    $hasActiveChild = false;
    if ($children) {
        foreach ($children as $child) {
            if (isset($child['routeName']) && request()->routeIs($child['routeName'])) {
                $hasActiveChild = true;
                break;
            }
        }
    }
@endphp

<li class="nav-item {{ $hasActiveChild ? 'menu-open' : '' }}">
    <a href="{{ $href }}" class="nav-link {{ $isActive || $hasActiveChild ? 'active' : '' }}">
        <i class="nav-icon {{ $iconClass }}"></i>
        <p>
            {{ $label }}
            @if($children)
                <i class="nav-arrow {{ $hasActiveChild ? 'bi bi-chevron-down' : 'bi bi-chevron-right' }}"></i>
            @endif
        </p>
    </a>
    @if($children)
        <ul class="nav nav-treeview" style="{{ $hasActiveChild ? '' : 'display: none;' }}">
            @foreach($children as $child)
                <li class="nav-item">
                    <a href="{{ $child['href'] }}" class="nav-link {{ isset($child['routeName']) && request()->routeIs($child['routeName']) ? 'active' : '' }}">
                        <i class="nav-icon {{ $child['iconClass'] ?? 'bi bi-circle' }}"></i>
                        <p>{{ $child['label'] }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</li>
