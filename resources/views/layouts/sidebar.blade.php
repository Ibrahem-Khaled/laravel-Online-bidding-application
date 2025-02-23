<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">لوحة التحكم</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>لوحة التحكم</span></a>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        الادارات
    </div>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>المستخدمين</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>التصنيفات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('live-streamings.index') }}">
            <i class="fas fa-fw fa-video"></i>
            <span>البثوث المباشرة</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('auctions.index') }}">
            <i class="fas fa-fw fa-bullhorn"></i>
            <span>المزادات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sliders.index') }}">
            <i class="fas fa-fw fa-sliders-h"></i>
            <span>السلايدرات</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('live-chat.index') }}">
            <i class="fas fa-fw fa-comments"></i>
            <span>الشات المساعدة والدعم</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
