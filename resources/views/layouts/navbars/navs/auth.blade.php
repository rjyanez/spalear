<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a  href="/home" class="h3 mb-0 text-white text-uppercase d-none d-lg-inline-block">
            {{ __('Spalear') }}
        </a>
        <!-- Form -->
        <!-- <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
            <div class="form-group mb-0">
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control" placeholder="Search" type="text">
                </div>
            </div>
        </form> -->
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item">
                <a-display class="nav-link pr-0" href="account" @selectLink="Vue.showSidebarMenu($event)">My account</a-display>
            </li>
            <li class="nav-item">
                <a class="nav-link pr-0" href="#">Teachers</a>
            </li>
            <li class="nav-item">
                <a-display class="nav-link pr-0" href="lessons" @selectLink="Vue.showSidebarMenu($event)">Lessons</a-display>
            </li>
        </ul>
    </div>
</nav>