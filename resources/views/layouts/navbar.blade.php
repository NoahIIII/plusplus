<div id="loading">
    <div id="loading-center">
    </div>
</div>
<!-- loader END -->
<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Sidebar  -->
    <div class="iq-sidebar">
        <div class="iq-sidebar-logo d-flex justify-content-between">
            <a href="{{ route('dashboard.index') }}">
                <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="">
                {{-- <span class="logo-text">Plus Plus</span> --}}
                {{-- <span>Plus Plus</span> --}}
            </a>
            <div class="iq-menu-bt-sidebar">
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                        <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidebar-scrollbar">
            <nav class="iq-sidebar-menu">
                <ul id="iq-sidebar-toggle" class="iq-menu">
                    <li class="iq-menu-title"><i
                            class="ri-subtract-line"></i><span>{{ ___('General Management') }}</span></li>
                    {{-- Users --}}
                    @can('manage-users')
                        <li class="{{ isActiveRoute('users.*') }}">
                            <a href="#users" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false"><i class="ri-user-line"></i><span>{{ ___('Manage Users') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                            <ul id="users" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                @can('manage-users')
                                    <li class="{{ isActiveRoute('users.create') }}"><a href="{{ route('users.create') }}"><i
                                                class="ri-user-add-line"></i>{{ ___('Add Users') }}</a>
                                    </li>
                                @endcan
                                @can('manage-users')
                                    <li class="{{ isActiveRoute('users.index') }}"><a href="{{ route('users.index') }}"><i
                                                class="ri-file-list-line"></i>{{ ___('Users List') }}</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    {{-- Staff Users --}}
                    @can('manage-staff-users')
                        <li class="{{ isActiveRoute('admins.*') }}">
                            <a href="#admins" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false"><i
                                    class="ri-admin-line"></i><span>{{ ___('Manage admins') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                            <ul id="admins" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                @can('manage-staff-users')
                                    <li class="{{ isActiveRoute('admins.create') }}"><a href="{{ route('admins.create') }}"><i
                                                class="ri-user-add-line"></i>{{ ___('Add admins') }}</a>
                                    </li>
                                @endcan
                                @can('manage-staff-users')
                                    <li class="{{ isActiveRoute('admins.index') }}"><a href="{{ route('admins.index') }}"><i
                                                class="ri-file-list-line"></i>{{ ___('admins List') }}</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcan

                    <li class="iq-menu-title"><i
                            class="ri-subtract-line"></i><span>{{ ___('Business Management') }}</span></li>
                    {{-- brands --}}
                    @can('manage-brands')
                        <li class="{{ isActiveRoute('brands.*') }}">
                            <a href="#brands" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false">
                                <i class="ri-store-2-line"></i><span>{{ ___('Brands') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                            </a>
                            <ul id="brands" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                @can('manage-brands')
                                    <li class="{{ isActiveRoute('brands.create') }}">
                                        <a href="{{ route('brands.create') }}">
                                            <i class="ri-add-box-line"></i>{{ ___('Add Brand') }}
                                        </a>
                                    </li>
                                @endcan

                                @foreach ($businessTypes as $businessType)
                                    <ul>
                                        <li>
                                            <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse"
                                                aria-expanded="false"><i
                                                    class="{{ $businessType->icon ?? 'ri-menu-line' }}"></i><span>{{ $businessType->getTranslation('name', app()->getLocale()) }}</span><i
                                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                            <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                <li
                                                    class="{{ isActiveRoute('brands.index', 'active', $businessType->slug) }}">
                                                    <a href="{{ route('brands.index', $businessType->slug) }}">
                                                        <i class="ri-file-list-line"></i>
                                                        {{ ___('Brands List') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
                            </ul>
                        </li>
                    @endcan

                    {{-- Categories --}}
                    @can('manage-categories')
                        <li class="{{ isActiveRoute('categories.*') }}">
                            <a href="#categories" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false">
                                <i class="ri-list-check"></i><span>{{ ___('categories') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                            </a>
                            <ul id="categories" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                @can('manage-categories')
                                    <li class="{{ isActiveRoute('categories.create') }}">
                                        <a href="{{ route('categories.create') }}">
                                            <i class="ri-add-box-line"></i>{{ ___('Add Category') }}
                                        </a>
                                    </li>
                                @endcan

                                @foreach ($businessTypes as $businessType)
                                    <ul>
                                        <li>
                                            <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse"
                                                aria-expanded="false"><i
                                                    class="{{ $businessType->icon ?? 'ri-menu-line' }}"></i><span>{{ $businessType->getTranslation('name', app()->getLocale()) }}</span><i
                                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                            <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                <li
                                                    class="{{ isActiveRoute('categories.index', 'active', $businessType->slug) }}">
                                                    <a href="{{ route('categories.index', $businessType->slug) }}">
                                                        <i class="ri-file-list-line"></i>
                                                        {{ ___('categories List') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
                            </ul>
                        </li>
                    @endcan
                    {{-- Products --}}
                    @can('manage-products')
                        <li class="{{ request()->segment(3) == 'products' || request()->segment(2) == 'products' ? 'active' : ''}}">
                            <a href="#products" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false">
                                <i class="ri-shopping-cart-line"></i><span>{{ ___('products') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                            </a>
                            <ul id="products" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">

                                @foreach ($businessTypes as $businessType)
                                    <ul>
                                        <li class="{{ request()->segment(1) == $businessType->slug || request()->segment(2) == $businessType->slug ? 'active' : ''}}">
                                            <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse"
                                                aria-expanded="false"><i
                                                    class="{{ $businessType->icon ?? 'ri-menu-line' }}"></i><span>{{ $businessType->getTranslation('name', app()->getLocale()) }}</span><i
                                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                            <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                <li
                                                    class="{{ isActiveRoute("$businessType->slug.products.index", 'active') }}">
                                                    <a href="{{ route("$businessType->slug.products.index") }}">
                                                        <i class="ri-file-list-line"></i>
                                                        {{ ___('Products List') }}
                                                    </a>
                                                </li>
                                                <li
                                                    class="{{ isActiveRoute("$businessType->slug.products.create", 'active') }}">
                                                    <a href="{{ route("$businessType->slug.products.create") }}">
                                                        <i class="ri-add-box-line"></i>
                                                        {{ ___('Create Product') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
                            </ul>
                        </li>
                    @endcan
                    {{-- discounts --}}
                    @can('manage-discounts')
                        <li class="{{ isActiveRoute('discounts.*') }}">
                            <a href="#discounts" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false">
                                <i class="ri-percent-line"></i><span>{{ ___('Discounts') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                            </a>
                            <ul id="discounts" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                @can('manage-discounts')
                                    <li class="{{ isActiveRoute('discounts.create') }}">
                                        <a href="{{ route('discounts.create') }}">
                                            <i class="ri-add-box-line"></i>{{ ___('Add Discount') }}
                                        </a>
                                    </li>
                                @endcan

                                @foreach ($businessTypes as $businessType)
                                    <ul>
                                        <li>
                                            <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse"
                                                aria-expanded="false"><i
                                                    class="{{ $businessType->icon ?? 'ri-menu-line' }}"></i><span>{{ $businessType->getTranslation('name', app()->getLocale()) }}</span><i
                                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                            <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                <li
                                                    class="{{ isActiveRoute('discounts.index', 'active', $businessType->slug) }}">
                                                    <a href="{{ route('discounts.index', $businessType->slug) }}">
                                                        <i class="ri-file-list-line"></i>
                                                        {{ ___('Discounts List') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
                            </ul>
                        </li>
                    @endcan
                    {{-- Sections --}}
                    @can('manage-sections')
                        <li class="{{ isActiveRoute('sections.*') }}">
                            <a href="#sections" class="iq-waves-effect collapsed" data-toggle="collapse"
                                aria-expanded="false">
                                <i class="ri-folder-3-line"></i><span>{{ ___('Sections') }}</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                            </a>
                            <ul id="sections" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <li class="{{ isActiveRoute('sections.create') }}">
                                        <a href="{{ route('sections.create') }}">
                                            <i class="ri-add-box-line"></i>{{ ___('Add Section') }}
                                        </a>
                                    </li>

                                @foreach ($businessTypes as $businessType)
                                    <ul>
                                        <li>
                                            <a href="#sub-menu" class="iq-waves-effect collapsed" data-toggle="collapse"
                                                aria-expanded="false"><i
                                                    class="{{ $businessType->icon ?? 'ri-menu-line' }}"></i><span>{{ $businessType->getTranslation('name', app()->getLocale()) }}</span><i
                                                    class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                                            <ul id="sub-menu" class="iq-submenu iq-submenu-data collapse">
                                                <li
                                                    class="{{ isActiveRoute('sections.index', 'active', $businessType->slug) }}">
                                                    <a href="{{ route('sections.index', $businessType->slug) }}">
                                                        <i class="ri-file-list-line"></i>
                                                        {{ ___('Sections List') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
                            </ul>
                        </li>
                    @endcan
                </ul>
            </nav>
            <div class="p-3"></div>
        </div>
    </div>
    <!-- TOP Nav Bar -->
    <div class="iq-top-navbar">
        <div class="iq-navbar-custom">
            <div class="iq-sidebar-logo">
                <div class="top-logo">
                    <a href="../index.html" class="logo">
                        <img src="../images/logo.gif" class="img-fluid" alt="">
                        <span>Plus Plus</span>
                    </a>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>
                <div class="iq-menu-bt align-self-center">
                    <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                        <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list">
                        <li class="nav-item">
                            <a class="search-toggle iq-waves-effect language-title" href="#">
                                {{ LaravelLocalization::getCurrentLocaleName() }} <i class="ri-arrow-down-s-line"></i>
                            </a>
                            <div class="iq-sub-dropdown">
                                @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <a class="iq-sub-card"
                                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                        {{ $properties['native'] }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="search-toggle iq-waves-effect">
                                <div id="lottie-beil"></div>
                                <span class="bg-danger dots"></span>
                            </a>
                            <div class="iq-sub-dropdown">
                                <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 ">
                                        <div class="bg-primary p-3">
                                            <h5 class="mb-0 text-white">{{ ___('All Notifications') }}
                                                {{-- <small
                                                    class="badge  badge-light float-right pt-1">4</small> --}}
                                                </h5>
                                        </div>


                                        {{-- <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center">
                                                <div class="">
                                                    <img class="avatar-40 rounded" src="../images/user/04.jpg"
                                                        alt="">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <h6 class="mb-0 ">New Mail from Fenny</h6>
                                                    <small class="float-right font-size-12">3 days ago</small>
                                                    <p class="mb-0">Jond Nik</p>
                                                </div>
                                            </div>
                                        </a> --}}
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="search-toggle iq-waves-effect">
                                <div id="lottie-mail"></div>
                                <span class="bg-primary count-mail"></span>
                            </a>
                            <div class="iq-sub-dropdown">
                                <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 ">
                                        <div class="bg-primary p-3">
                                            <h5 class="mb-0 text-white">{{ ___('All Messages') }}
                                                {{-- <small
                                                    class="badge  badge-light float-right pt-1">5</small> --}}
                                                </h5>
                                        </div>

                                        {{-- <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center">
                                                <div class="">
                                                    <img class="avatar-40 rounded" src="../images/user/05.jpg"
                                                        alt="">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <h6 class="mb-0 ">Lorem Ipsum generators</h6>
                                                    <small class="float-left font-size-12">5 Dec</small>
                                                </div>
                                            </div>
                                        </a> --}}
                                    </div>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
                <ul class="navbar-list">

                    <li>
                        <a href="#"
                            class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                            <img src="{{ getImageUrl(auth('staff_users')->user()->staff_user_img) ?? asset('assets/images/user/default_user.png') }}"
                                class="img-fluid rounded mr-3" alt="user">
                            <div class="caption">
                                <h6 class="mb-0 line-height text-white">{{ auth('staff_users')->user()->name }}</h6>
                                {{-- <span class="font-size-12 text-white">Available</span> --}}
                            </div>
                        </a>
                        <div class="iq-sub-dropdown iq-user-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div class="iq-card-body p-0 ">
                                    <div class="bg-primary p-3">
                                        <h5 class="mb-0 text-white line-height">{{ ___('Hello') }}
                                            {{ auth('staff_users')->user()->name }}</h5>
                                        {{-- <span class="text-white font-size-12">Available</span> --}}
                                    </div>

                                    <a href="" class="iq-sub-card iq-bg-primary-hover">
                                        <div class="media align-items-center">
                                            <div class="rounded iq-card-icon iq-bg-primary">
                                                <i class="ri-account-box-line"></i>
                                            </div>
                                            <div class="media-body ml-3">
                                                <h6 class="mb-0 ">{{ ___('Account settings') }}</h6>
                                                <p class="mb-0 font-size-12">
                                                    {{ ___('Manage your account parameters.') }}</p>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="d-inline-block w-100 text-center p-3">
                                        <form action="{{ route('logout') }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary dark-btn-primary">
                                                {{ ___('Sign out') }} <i class="ri-login-box-line ml-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
