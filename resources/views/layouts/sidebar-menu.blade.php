<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <router-link to="/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt blue"></i>
          <p>
            Dashboard
          </p>
        </router-link>
      </li>
        <li class="nav-item">
            <router-link to="/monitor" class="nav-link">
                <i class="nav-icon fas fa-globe blue "></i>
                <p>
                    Monitor URL
                </p>
            </router-link>
        </li>
{{--        <li class="nav-item">--}}
{{--            <router-link to="/tracking" class="nav-link">--}}
{{--                <i class="nav-icon fas fa-globe yellow "></i>--}}
{{--                <p>--}}
{{--                     Tracking URL--}}
{{--                </p>--}}
{{--            </router-link>--}}
{{--        </li>--}}
        @can('isAdmin')
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-user-cog blueMenu"></i>
                    <p>
                        Manutenção
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <router-link to="/log-activity" class="nav-link">
                            <i class="nav-icon fas fa-database yellowMenu"></i>
                            <p>
                                Log atividades
                            </p>
                        </router-link>
                    </li>
                </ul>
            </li>

        @endcan
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off light"></i>
          <p>
            {{ __('Logout') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>
