<div class="c-wrapper">
    <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
      <ul class="c-header-nav ml-auto mr-4">
        <li class="c-header-nav-item dropdown"><a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button"
            aria-haspopup="true" aria-expanded="false">
            <div class="c-avatar"><img class="c-avatar-img" src="{{ url('/assets/img/avatars/6.jpg') }}"
                alt="user@email.com"></div>
          </a>
          <div class="dropdown-menu dropdown-menu-right pt-0">
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <svg class="c-icon mr-2">
                <use xlink:href="{{ url('/icons/sprites/free.svg#cil-account-logout') }}"></use>
              </svg>
              Logout
            </a>
          </div>
        </li>
      </ul>
      {{-- <div class="c-subheader px-3">
        <ol class="breadcrumb border-0 m-0">
          <?php
                $path = explode('.',Request::route()->getName());
                // echo print_r($path);
                $segments = '';
              ?>
          @for($i = 0; $i < count($path); $i++) <?php $segments .= '/'. $path[$i]; ?> @if($i < count($path)) <li
            class="breadcrumb-item">{{ ucwords(str_replace('_',' ',$path[$i])) }}</li>
            @else
            <li class="breadcrumb-item active">{{ ucwords($path($i)) }}</li>
            @endif
            @endfor
        </ol>
      </div> --}}
    </header>
