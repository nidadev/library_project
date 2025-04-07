<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Aside Toolbarl-->

    <!--end::Aside Toolbarl-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y px-2 my-5 my-lg-5  " id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
            data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px"
            style="overflow-x: hidden !important; height: 778px;">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-white"
                id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item">
                    <a class="menu-link gap-3 {{ request()->routeIs('user.home') ? 'active' : '' }}"
                        href="{{ route('admin.home') }}">
                        <span class="menu-bullet">
                            <i class="bi bi-house fs-3 text-white"></i>
                        </span>
                        <span class="menu-title text-white">Home</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link gap-3 {{ request()->routeIs('user.bookpage.dashboard') ? 'active' : '' }}"
                        href="{{ route('user.bookpage.dashboard') }}">
                        <span class="menu-bullet">
                            <i class="bi bi-book fs-3 text-white"></i>
                        </span>
                        <span class="menu-title text-white">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link gap-3 {{ request()->routeIs('user.bookpage.index') ? 'active' : '' }}"
                        href="{{ route('user.bookpage.index') }}">
                        <span class="menu-bullet">
                            <i class="bi bi-book fs-3 text-white"></i>
                        </span>
                        <span class="menu-title text-white">Book Page</span>
                    </a>
                </div>


                <!--div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('user.home') ? 'show' : '' }}">
                    <!-- Certificate Parent Menu -->
                    <!--span class="menu-link py-2">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-post fs-3 text-white"></i>
                            </span>
                        </span>
                        <span class="menu-title text-white">Certificate</span>
                        <span class="menu-arrow text-white"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion {{ request()->routeIs('user.home') ? 'show' : '' }}"
                        kt-hidden-height="163"
                        style="display: {{ request()->routeIs('user.home') ? 'block' : 'none' }}; overflow: hidden;">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('user.home') ? 'active' : '' }}"
                                href="{{ route('user.home') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <i class="bi bi-calendar-event fs-3 text-white"></i>
                                    </span>
                                </span>
                                <span class="menu-title text-white">Name</span>
                            </a>
                        </div>
                    </div>
                </div--><!---- certificate side bar -->




            </div>
        </div>
        <!--end:Menu sub-->
    </div>
    <!--begin::Menu-->
    <!--end::Footer-->
</div>
