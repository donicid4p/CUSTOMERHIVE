@extends('crudbooster::admin_template',['target_layout' => $target_layout])

@section('content')
@if($index_statistic)
<div id='box-statistic' class='row'>
    @foreach($index_statistic as $stat)
    <div class="{{ ($stat['width'])?:'col-sm-3' }}">
        <div class="small-box bg-{{ $stat['color']?:'red' }}">
            <div class="inner">
                <h3>{{ $stat['count'] }}</h3>
                <p>{{ $stat['label'] }}</p>
            </div>
            <div class="icon">
                <i class="{{ $stat['icon'] }}"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if(!is_null($pre_index_html) && !empty($pre_index_html))
{!! $pre_index_html !!}
@endif

@if(g('return_url'))
<p>
    <a href='{{g("return_url")}}'>
        <i class='fa fa-chevron-circle-{{ trans(' crudbooster.left') }}'></i>&nbsp;
        {{trans('crudbooster.form_back_to_list',['module'=>urldecode(g('label'))])}}
    </a>
</p>
@endif

@isset($parent_table)
<div class="box box-default">
    <div class="box-body table-responsive no-padding">
        <table class='table table-bordered'>
            <tbody>
                <tr class='active'>
                    <td colspan="2"><strong><i class='fa fa-bars'></i> {{ ucwords(urldecode(g('label'))) }}</strong>
                    </td>
                </tr>
                <?php
                    $columns = explode(',',urldecode(g('parent_columns')));
                   ?>
                @foreach($columns as $c)
                <tr>
                    <td width="25%"><strong>
                            @if(urldecode(g('parent_columns_alias')))
                            {{explode(',',urldecode(g('parent_columns_alias')))[$loop->index]}}
                            @else
                            {{ ucwords(str_replace('_',' ',$c)) }}
                            @endif
                        </strong></td>
                    <td> {{ $parent_table->$c }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endisset

<div class="box">
    <div class="box-header mb-3">
        @if($button_bulk_action && ( ($button_delete && CRUDBooster::isDelete()) || $button_selected) )
        <div class="pull-{{ trans('crudbooster.left') }}">
            <div class="selected-action" style="display:inline-block;position:relative;">
                <button type="button" class="btn btn-sm btn-default  dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class='fa fa-check-square-o'></i>
                    {{trans("crudbooster.button_selected_action")}}
                    </button>
                <ul class="dropdown-menu">
<?php
                                $parameters = Request::all();
                                unset($parameters['q']);
                                $build_query = urldecode(http_build_query($parameters));
                                $build_query = ($build_query) ? "?".$build_query : "";
                                $build_query = (Request::all()) ? $build_query : "";
                                $module = CRUDBooster::getCurrentModule();

//if $module->path contians 'mg_'
if(strpos($module->path, 'mg_') !== false || $module->table_name == 'cms_users') { ?>
                    <li class="dropdown-item" style="padding: 0px">

                    <a  style="display: block; width: 100%; padding: 8px 16px;" href="javascript:void(0)" id='mass_editing_button'
                                    data-url-parameter='{{$build_query}}' title='Mass Edit' >
                                    <i class="fa fa-pencil"></i> Mass Edit
                                </a>
                    </li>


<?php }

?>


                    
                    @if($button_delete && CRUDBooster::isDelete())
                    <li  class="dropdown-item" style="padding: 0px" >
                        <a style="display: block; width: 100%; padding: 8px 16px;" href="javascript:void(0)" data-name='delete' title="{{trans('crudbooster.action_delete_selected')}}">
                            <i class="fa fa-trash"></i> {{trans('crudbooster.action_delete_selected')}}
                        </a>
                    </li>
                    @endif

                    @if($button_selected)
                    @foreach($button_selected as $button)
                    <li  class="dropdown-item">
                        <a href="javascript:void(0)" data-name='{{$button["name"]}}' title='{{$button["label"]}}'>
                            <i class="fa fa-{{$button['icon']}}"></i> {{$button['label']}}
                        </a>
                    </li>
                    @endforeach
                    @endif

                </ul><!--end-dropdown-menu-->
            </div><!--end-selected-action-->
        </div><!--end-pull-left-->
        @endif
        <div class="box-tools pull-{{ trans('crudbooster.right') }}"
            style="position: relative;margin-top: -5px;margin-right: -10px">
            <?php
                                $parameters = Request::all();
                                unset($parameters['q']);
                                $build_query = urldecode(http_build_query($parameters));
                                $build_query = ($build_query) ? "?".$build_query : "";
                                $build_query = (Request::all()) ? $build_query : "";
                                ?>

            @if($button_filter)
            <a  href="javascript:void(0)" id='btn_advanced_filter'
                data-url-parameter='{{$build_query}}' title='{{trans('crudbooster.filter_dialog_title')}}'
                class="btn btn-sm btn-default {{(Request::get('filter_column'))?'active':''}}">
                <i class="fa fa-filter"></i> {{trans("crudbooster.button_filter")}}
            </a>
            @endif

            <form method='get' style="display:inline-block;width: 260px;" action='{{Request::url()}}'>
                <div class="input-group">
                    <input type="text" name="q" value="{{ Request::get('q') }}"
                        class="form-control input-sm pull-{{ trans('crudbooster.right') }}"
                        placeholder="{{trans('crudbooster.filter_search')}}" />
                    {!! CRUDBooster::getUrlParameters(['q']) !!}
                    <div class="input-group-btn">
                        @if(Request::get('q'))

                        <button type='button' onclick='location.href="{{ CRUDBooster::mainpath().$build_query}}"'
                            title="{{trans('crudbooster.button_reset')}}" class='btn  btn-warning'><i
                                class='fa fa-ban'></i></button>
                        @endif
                        <button type='submit' class="btn  btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>


            <form method='get' id='form-limit-paging' style="display:inline-block" action='{{Request::url()}}'>
                {!! CRUDBooster::getUrlParameters(['limit']) !!}
                <div class="input-group">
                    <select onchange="$('#form-limit-paging').submit()" name='limit' style="width: 56px;"
                        class='form-control input-sm'>
                        <option {{($limit==5)?'selected':''}} value='5'>5</option>
                        <option {{($limit==10)?'selected':''}} value='10'>10</option>
                        <option {{($limit==20)?'selected':''}} value='20'>20</option>
                        <option {{($limit==25)?'selected':''}} value='25'>25</option>
                        <option {{($limit==50)?'selected':''}} value='50'>50</option>
                        <option {{($limit==100)?'selected':''}} value='100'>100</option>
                        <option {{($limit==200)?'selected':''}} value='200'>200</option>
                    </select>
                </div>
            </form>

        </div>

        <br style="clear:both" />

    </div>
    <div class="box-body table-responsive no-padding">
        @include("crudbooster::default.table")
    </div>
</div>

@if(!is_null($post_index_html) && !empty($post_index_html))
{!! $post_index_html !!}
@endif

@endsection