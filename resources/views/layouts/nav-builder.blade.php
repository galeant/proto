<div class="c-sidebar-brand">
    <img class="c-sidebar-brand-full" src="/assets/brand/coreui-base-white.svg" width="118" height="46" alt="CoreUI Logo">
</div>
 <ul class="c-sidebar-nav ps ps--active-y">
    <li class="c-sidebar-nav-item">
       <a class="c-sidebar-nav-link" href="#">
            <i class="c-sidebar-nav-icon fa fa-home"></i>Dashboard
       </a>
    </li>
    <li class="c-sidebar-nav-item">
       <a class="c-sidebar-nav-link" href="#">
          <i class="c-sidebar-nav-icon fa fa-check-circle"></i>Assement Manager
       </a>
    </li>
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <i class="c-sidebar-nav-icon fa fa-desktop"></i>Report
        </a>
     <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="#"><span
                 class="c-sidebar-nav-icon"></span> Staff</a></li>
        <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="#"><span
                 class="c-sidebar-nav-icon"></span> Assessment</a></li>
     </ul>
    </li>
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
            <i class="c-sidebar-nav-icon fa fa-book"></i>Master Data
        </a>
       <ul class="c-sidebar-nav-dropdown-items">
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{route('master_data.assessment.view')}}"><span
                   class="c-sidebar-nav-icon"></span> Assesment</a></li>
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{route('master_data.branch.view')}}"><span
                   class="c-sidebar-nav-icon"></span> Branch</a></li>
       </ul>
    </li>

 </ul>
 <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent"
    data-class="c-sidebar-minimized"></button>
 </div>
