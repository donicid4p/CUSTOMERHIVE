<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-{{ trans('crudbooster.left') }} image">
                <img src="{{ CRUDBooster::myPhoto() }}" class="img-circle" alt="{{ trans('crudbooster.user_image') }}"/>
            </div>
            <div class="pull-{{ trans('crudbooster.left') }} info">
                <p>{{ CRUDBooster::myName() }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('crudbooster.online') }}</a>
            </div>
        </div>

        <div class='main-menu'>
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">{{trans("crudbooster.menu_navigation")}}
                  <div class="my-collapse-sidebar pull-right" data-collapse-btn="1">
                    <i class="fa fa-minus"></i>
                  </div>
                </li>

                <?php $dashboard = CRUDBooster::sidebarDashboard();?>
                @if($dashboard)
                    <li data-id='{{$dashboard->id}}' data-collapse="1" class="{{ (Request::is(config('crudbooster.ADMIN_PATH'))) ? 'active' : '' }}">
                      <a href='{{CRUDBooster::adminPath()}}' class='{{($dashboard->color)?"text-".$dashboard->color:""}}'>
                        <i class='fa fa-dashboard'></i>
                        <span>{{trans("crudbooster.text_dashboard")}}</span>
                      </a>
                    </li>
                @endif
                @foreach(CRUDBooster::sidebarMenu() as $menu)
                    <?php
                      if($menu->new_tab){
                        $target='target="_blank"';
                      }
                      else{
                        $target='';
                      }
                    ?>
                    <li data-id='{{$menu->id}}' data-collapse="1" class='{{(!empty($menu->children))?"treeview":""}} {{ (Request::is($menu->url_path."*"))?"active":""}}'>
                        <a {{ $target }} href='{{ ($menu->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$menu->url }}' class='{{($menu->color)?"text-".$menu->color:""}}'>
                            <i class='{{$menu->icon}} {{($menu->color)?"text-".$menu->color:""}}'></i>
                            <span>{{$menu->name}}</span>
                            @if(!empty($menu->children))
                            <i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>
                            @endif
                        </a>
                        @if(!empty($menu->children))
                            <ul class="treeview-menu">
                                @foreach($menu->children as $child)
                                    <li data-id='{{$child->id}}' class='{{(Request::is($child->url_path .= !ends_with(Request::decodedPath(), $child->url_path) ? "/*" : ""))?"active":""}}'>
                                        <a href='{{ ($child->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$child->url}}' class='{{($child->color)?"text-".$child->color:""}}'>
                                            <i class='{{$child->icon}}'></i>
                                            <span>{{$child->name}}</span>
                                            @if(!empty($child->children))
                                            <i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>
                                            @endif
                                            @if(!empty($child->children))
                                              <ul class="treeview-menu">
                                                  @foreach($child->children as $grandchild)
                                                    <li data-id='{{$grandchild->id}}' class='{{(Request::is($child->url_path .= !ends_with(Request::decodedPath(), $grandchild->url_path) ? "/*" : ""))?"active":""}}'>
                                                      <a href='{{ ($grandchild->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$grandchild->url}}' class='{{($grandchild->color)?"text-".$grandchild->color:""}}'>
                                                          <i class='{{$grandchild->icon}}'></i>
                                                          <span>{{$grandchild->name}}</span>
                                                      </a>
                                                    </li>
                                                  @endforeach
                                              </ul>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li data-id='3' data-collapse="3" class='treeview'>
                  <a href='#' class=''>
                    <i class='fa fa-cog'></i>
                    <span>test grandpa</span>
                    <i class="fa fa-angle-right pull-right"></i>
                    <ul class="treeview-menu">
                      <li data-id='2' class=''>
                        <a href='' class=''>
                          <i class='fa fa-cog'></i>
                          <span>test pa</span>
                          <i class="fa fa-angle-right pull-right"></i>
                          <ul class="treeview-menu">
                            <li data-id='1' class=''>
                              <a href='' class=''>
                                <i class='fa fa-cog'></i>
                                <span>test kid</span>
                              </a>
                            </li>
                          </ul>
                        </a>
                      </li>
                      <li data-id='22' class=''>
                        <a href='' class=''>
                          <i class='fa fa-cog'></i>
                          <span>test pa</span>
                        </a>
                      </li>
                    </ul>
                  </li>

                @if(CRUDBooster::isSuperadmin() OR CRUDBooster::myPrivilegeName() === 'Advanced' )

                  <li class="header">{{ trans('crudbooster.UserPermissions') }}
                    <div class="my-collapse-sidebar pull-right" data-collapse-btn="2">
                      <i class="fa fa-minus"></i>
                    </div>
                  </li>

                  @if(CRUDBooster::isSuperadmin())
                  <li data-collapse="2" class='treeview'>
                      <a href='#'><i class='fa fa-industry'></i> <span>{{ trans('crudbooster.Tenants') }}</span> <i
                                  class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                      <ul class='treeview-menu'>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/tenants/add*')) ? 'active' : '' }}"><a
                                      href='{{Route("AdminTenantsControllerGetAdd")}}'>{{ $current_path }}<i class='fa fa-plus'></i>
                                  <span>{{ trans('crudbooster.Add_New_Tenant') }}</span></a></li>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/tenants')) ? 'active' : '' }}"><a
                                      href='{{Route("AdminTenantsControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                  <span>{{ trans('crudbooster.List_Tenants') }}</span></a></li>
                      </ul>
                  </li>

                  <li data-collapse="2" class='treeview'>
                      <a href='#'><i class='fa fa-key'></i> <span>{{ trans('crudbooster.Roles') }}</span> <i
                                  class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                      <ul class='treeview-menu'>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/privileges/add*')) ? 'active' : '' }}"><a
                                      href='{{Route("PrivilegesControllerGetAdd")}}'>{{ $current_path }}<i class='fa fa-plus'></i>
                                  <span>{{ trans('crudbooster.Add_New_Privilege') }}</span></a></li>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/privileges')) ? 'active' : '' }}"><a
                                      href='{{Route("PrivilegesControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                  <span>{{ trans('crudbooster.List_Privilege') }}</span></a></li>
                      </ul>
                  </li>
                  @endif

                  <li data-collapse="2" class='treeview'>
                      <a href='#'><i class='fa fa-users'></i> <span>{{ trans('crudbooster.Groups') }}</span> <i
                                  class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                      <ul class='treeview-menu'>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/groups/add*')) ? 'active' : '' }}"><a
                                      href='{{Route("AdminGroupsControllerGetAdd")}}'>{{ $current_path }}<i class='fa fa-plus'></i>
                                  <span>{{ trans('crudbooster.Add_New_Group') }}</span></a></li>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/groups')) ? 'active' : '' }}"><a
                                      href='{{Route("AdminGroupsControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                  <span>{{ trans('crudbooster.List_Groups') }}</span></a></li>
                      </ul>
                  </li>

                  <li data-collapse="2" class='treeview'>
                      <a href='#'>
                        <i class='fa fa-user'></i> <span>{{ trans('crudbooster.Users') }}</span>
                        <i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>
                      </a>
                      <ul class='treeview-menu'>
                        <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/users/add*')) ? 'active' : '' }}">
                          <a href='{{Route("AdminCmsUsersControllerGetAdd")}}'>
                            <i class='fa fa-plus'></i>
                            <span>{{ trans('crudbooster.add_user') }}</span>
                          </a>
                        </li>
                        <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/users')) ? 'active' : '' }}">
                          <a href='{{Route("AdminCmsUsersControllerGetIndex")}}'>
                            <i class='fa fa-bars'></i>
                            <span>{{ trans('crudbooster.List_users') }}</span>
                          </a>
                        </li>
                      </ul>
                  </li>

                  <li data-collapse="2" class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/logs*')) ? 'active' : '' }}">
                    <a href='{{Route("LogsControllerGetIndex")}}'>
                      <i class='fa fa-flag'></i> <span>{{ trans('crudbooster.User_Access_Log') }}</span>
                    </a>
                  </li>

                @endif

                @if(CRUDBooster::isSuperadmin() OR CRUDBooster::myPrivilegeName() === 'Advanced' )
                    <li class="header">{{ trans('crudbooster.superadmin') }}
                      <div class="my-collapse-sidebar pull-right" data-collapse-btn="3">
                        <i class="fa fa-minus"></i>
                      </div>
                    </li>

                    <li data-collapse="3" class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/menu_management*')) ? 'active' : '' }}"><a
                                href='{{Route("MenusControllerGetIndex")}}'><i class='fa fa-bars'></i>
                            <span>{{ trans('crudbooster.Menu_Management') }}</span></a></li>

                    @if(CRUDBooster::isSuperadmin())
                    <li data-collapse="3" class="treeview">
                        <a href="#"><i class='fa fa-wrench'></i> <span>{{ trans('crudbooster.settings') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class="treeview-menu">
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/settings/add*')) ? 'active' : '' }}"><a
                                        href='{{route("SettingsControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Setting') }}</span></a></li>
                            <?php
                            $groupSetting = DB::table('cms_settings')->groupby('group_setting')->pluck('group_setting');
                            foreach($groupSetting as $gs):
                            ?>
                            <li class="<?=($gs == Request::get('group')) ? 'active' : ''?>"><a
                                        href='{{route("SettingsControllerGetShow")}}?group={{urlencode($gs)}}&m=0'><i class='fa fa-wrench'></i>
                                    <span>{{$gs}}</span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>

                    <li data-collapse="3" class='treeview'>
                        <a href='#'>
                          <i class='fa fa-th'></i> <span>{{ trans('crudbooster.Module_Generator') }}</span> <i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>
                        </a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/module_generator/step1')) ? 'active' : '' }}">
                              <a href='{{Route("ModulsControllerGetStep1")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Module') }}</span>
                                  </a>
                                </li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/module_generator')) ? 'active' : '' }}">
                              <a href='{{Route("ModulsControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Module') }}</span>
                                  </a>
                                  </li>
                        </ul>
                    </li>

                    <li data-collapse="3" class='treeview'>
                        <a href='#'><i class='fa fa-signal'></i> <span>{{ trans('crudbooster.Qlik_Items') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/qlik_items/add')) ? 'active' : '' }}"><a
                                        href='{{Route("AdminQlikItemsControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Qlikitem') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/qlik_items')) ? 'active' : '' }}"><a
                                        href='{{Route("AdminQlikItemsControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Qlikitem') }}</span></a></li>
                        </ul>
                    </li>

                    <li data-collapse="3" class='treeview'>
                        <a href='#'>
                          <img class="menu qlik_logo" src=/images/qlik_logo.png />
                          <span>{{ trans('crudbooster.Qlik_Development_Tools') }}</span> <i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>
                        </a>
                        <ul class='treeview-menu'>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/qlik_items/add')) ? 'active' : '' }}">
                            <a href='{{Route("QlikServerSenseHub")}}'>
                              <i class='fa fa-dashboard'></i>
                              <span>{{ trans('crudbooster.Qlik_Sense_Hub') }}</span>
                            </a>
                          </li>
                          <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/qlik_items')) ? 'active' : '' }}">
                            <a href='{{Route("QlikServerSenseQMC")}}'>
                              <i class='fa fa-cog'></i>
                              <span>{{ trans('crudbooster.Qlik_Sense_QMC') }}</span>
                            </a>
                          </li>
                        </ul>
                    </li>

                    <li data-collapse="3" class='treeview'>
                        <a href='#'><i class='fa fa-dashboard'></i> <span>{{ trans('crudbooster.Statistic_Builder') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/statistic_builder/add')) ? 'active' : '' }}"><a
                                        href='{{Route("StatisticBuilderControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Statistic') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/statistic_builder')) ? 'active' : '' }}"><a
                                        href='{{Route("StatisticBuilderControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Statistic') }}</span></a></li>
                        </ul>
                    </li>

                    <li data-collapse="3" class='treeview'>
                        <a href='#'><i class='fa fa-fire'></i> <span>{{ trans('crudbooster.API_Generator') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator/generator*')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetGenerator")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_API') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.list_API') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator/screet-key*')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetScreetKey")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.Generate_Screet_Key') }}</span></a></li>
                        </ul>
                    </li>

                    <li data-collapse="3" class='treeview'>
                        <a href='#'><i class='fa fa-envelope-o'></i> <span>{{ trans('crudbooster.Email_Templates') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/email_templates/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("EmailTemplatesControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Email') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/email_templates')) ? 'active' : '' }}"><a
                                        href='{{Route("EmailTemplatesControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Email_Template') }}</span></a></li>
                        </ul>
                    </li>
                    @endif

                @endif

            </ul><!-- /.sidebar-menu -->

        </div>

    </section>
    <!-- /.sidebar -->
</aside>
