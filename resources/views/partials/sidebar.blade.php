@php
    function isActiveRoute($routeNames, $output = 'active')
    {
        foreach ((array) $routeNames as $routeName) {
            if (Route::currentRouteName() == $routeName) {
                return $output;
            }
        }
        return null;
    }

    function areActiveRoutes($routeNames, $output = 'active')
    {
        foreach ((array) $routeNames as $routeName) {
            if (Route::currentRouteName() == $routeName) {
                return $output;
            }
        }
        return null;
    }
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <li class="nav-item nav-category">Products</li>

        <li class="nav-item {{ isActiveRoute(['product.index', 'product.create', 'product.edit']) }}">
            <a class="nav-link" href="{{ route('product.index') }}">
                <i class="bi bi-bucket menu-icon"></i>
                <span class="menu-title">Product</span>
            </a>
        </li>

        <li class="nav-item {{ isActiveRoute(['item.index', 'item.create', 'item.edit']) }}">
            <a class="nav-link" href="{{ route('item.index') }}">
                <i class="bi bi-diagram-3 menu-icon"></i>
                <span class="menu-title">Category</span>
            </a>
        </li>
        
    </ul>
</nav>
