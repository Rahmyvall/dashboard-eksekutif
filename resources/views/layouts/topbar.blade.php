<div class="header">
     <div class="header-left">
          <a href="" class="burger-menu"><i data-feather="menu"></i></a>

          <div class="header-search">
               <i data-feather="search"></i>
               <input type="search" class="form-control" placeholder="What are you looking for?">
          </div><!-- header-search -->

     </div><!-- header-left -->

     <div class="header-right">
          <a href="" class="header-help-link"><i data-feather="help-circle"></i></a>

          <div class="dropdown dropdown-notification">
               <!-- Theme Toggle -->
               <a href="javascript:void(0);" class="header-help-link" id="themeToggle" data-theme-toggle
                    title="Change Theme" aria-label="Change Theme" aria-pressed="false">

                    <i data-feather="moon" id="themeIcon"></i>

               </a>
               <a href="javascript:void(0);" class="dropdown-link new" data-toggle="dropdown"><i data-feather="bell"></i></a>
               <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-menu-header">
                         <h6>Notifications</h6>
                         <a href=""><i data-feather="more-vertical"></i></a>
                    </div><!-- dropdown-menu-header -->
                    <div class="dropdown-menu-body">
                         <a href="" class="dropdown-item">
                              <div class="avatar"><span
                                        class="avatar-initial rounded-circle text-primary bg-primary-light">s</span>
                              </div>
                              <div class="dropdown-item-body">
                                   <p><strong>Socrates Itumay</strong> marked the task as completed.</p>
                                   <span>5 hours ago</span>
                              </div>
                         </a>
                         <a href="" class="dropdown-item">
                              <div class="avatar"><span
                                        class="avatar-initial rounded-circle tx-pink bg-pink-light">r</span>
                              </div>
                              <div class="dropdown-item-body">
                                   <p><strong>Reynante Labares</strong> marked the task as incomplete.
                                   </p>
                                   <span>8 hours ago</span>
                              </div>
                         </a>
                         <a href="" class="dropdown-item">
                              <div class="avatar"><span
                                        class="avatar-initial rounded-circle tx-success bg-success-light">d</span>
                              </div>
                              <div class="dropdown-item-body">
                                   <p><strong>Dyanne Aceron</strong> responded to your comment on this
                                        <strong>post</strong>.
                                   </p>
                                   <span>a day ago</span>
                              </div>
                         </a>
                         <a href="" class="dropdown-item">
                              <div class="avatar"><span
                                        class="avatar-initial rounded-circle tx-indigo bg-indigo-light">k</span>
                              </div>
                              <div class="dropdown-item-body">
                                   <p><strong>Kirby Avendula</strong> marked the task as incomplete.</p>
                                   <span>2 days ago</span>
                              </div>
                         </a>
                    </div><!-- dropdown-menu-body -->
                    <div class="dropdown-menu-footer">
                         <a href="">View All Notifications</a>
                    </div>
               </div><!-- dropdown-menu -->

          </div>
          <div class="dropdown dropdown-loggeduser">
               <a href="javascript:void(0);" class="dropdown-link" data-toggle="dropdown">

                    <div class="avatar avatar-sm">

                         <img src="{{ asset('backend/assets/img/logo.png') }}" class="rounded-circle" alt="User Avatar">

                    </div>

               </a>
               <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-menu-header">
                         <div class="media align-items-center">

                              <div class="avatar">
                                   <img src="{{ asset('backend/assets/img/logo.png') }}" class="rounded-circle"
                                        alt="Avatar {{ auth()->user()->name ?? 'Administrator' }}">
                              </div>

                              <div class="media-body mg-l-10">
                                   <h6 class="mg-b-0">
                                        {{ auth()->user()->name ?? 'Administrator' }}
                                   </h6>

                                   <span>
                                        {{ auth()->user()->email ?? 'Admin Dashboard' }}
                                   </span>
                              </div>

                         </div>
                    </div>

                    <div class="dropdown-menu-body">

                         <a href="#" class="dropdown-item">
                              <i data-feather="user"></i>
                              View Profile
                         </a>

                         <a href="#" class="dropdown-item">
                              <i data-feather="edit-2"></i>
                              Edit Profile
                         </a>

                         <a href="#" class="dropdown-item">
                              <i data-feather="briefcase"></i>
                              Account Settings
                         </a>

                         <a href="#" class="dropdown-item">
                              <i data-feather="shield"></i>
                              Privacy Settings
                         </a>

                         <form action="{{ route('logout') }}" method="POST" class="mg-0">
                              @csrf

                              <button type="submit" class="dropdown-item border-0 bg-transparent tx-left wd-100p"
                                   onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                                   <i data-feather="log-out"></i>
                                   Sign Out
                              </button>
                         </form>

                    </div>
               </div>

          </div>
     </div><!-- header-right -->

</div><!-- header -->
