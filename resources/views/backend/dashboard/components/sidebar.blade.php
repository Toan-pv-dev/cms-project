@php
    $segment = request()->segment(1);
    // dd($segment);
@endphp
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David
                                    Williams</strong>
                            </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span>
                        </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            @php
                $sidebarModules = __('sidebar.module');
                // dd($sidebarModules); // Uncomment để debug
            @endphp
            @foreach ($sidebarModules as $key => $val)
                <li class="{{ in_array($segment, $val['name']) ? 'active' : '' }}">
                    <a href="#">
                        {!! $val['icon'] !!} <span class="nav-label">{{ $val['title'] }}</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        @foreach ($val['subModule'] as $subModule)
                            <li>
                                @if (!empty($subModule['route']))
                                    <a href="{{ route($subModule['route']) }}">{{ $subModule['title'] }}</a>
                                @else
                                    <a href="javascript:void(0)">{{ $subModule['title'] }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach

        </ul>

    </div>
</nav>
