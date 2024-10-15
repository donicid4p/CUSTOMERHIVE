<div class='mb-3 row form-datepicker {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='mb-3 form-group-{{$name}}'
     style="{{@$form['style']}}">
    <label class='col-form-label col-sm-2'>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">
        <div class="input-group">

            <span class="input-group-text"><a href='javascript:void(0)' onclick='$("#{{$name}}").data("daterangepicker").toggle();'><i
                            class='fa fa-calendar'></i></a></span>

            <input type='text' title="{{$form['label']}}" readonly
                   {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} class='form-control notfocus input_datetime' name="{{$name}}" id="{{$name}}"
                   value='{{$value}}' />
        </div>
        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>
    </div>
</div>
