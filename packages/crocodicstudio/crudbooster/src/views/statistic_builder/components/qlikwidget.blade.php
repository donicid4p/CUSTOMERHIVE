@php 
use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;

use crocodicstudio\crudbooster\controllers\QlikMashupController;


    $mashup = QlikMashupController::getMashupFromCompID($componentID);
    $token = HelpersQlikHelper::getJWTToken(1, 3);
    $conf = QlikMashupController::getConf(3);



@endphp 
@if($command=='layout')

<style>
.qlikwidget {
	height: 50vh;
}
</style>


<div id='{{$componentID}}' class='border-box'>

<h1>{{isset($mashup) ? $mashup->mashupname : 'Choose Conf'}}</h1>
<iframe src="/mashup/{{$componentID}}" frameborder="0" style="width: 100%;height: 80%;"></iframe>

    <div class='action pull-right'>
        <a href='javascript:void(0)' data-componentid='{{$componentID}}' data-name='Qlik Widget'
            class='btn-edit-component'><i class='fa fa-pencil'></i></a>
        &nbsp;
        <a href='javascript:void(0)' data-componentid='{{$componentID}}' class='btn-delete-component'><i
                class='fa fa-trash'></i></a>
    </div>
    </div>
 @elseif($command=='configuration')

<div id='{{$componentID}}'>
<iframe src="/mashup-objects/{{$mashup}}" id="configuration" frameborder="0" style="width: 100%;height: 80%;"></iframe>
</div>


<script defer >



function create_object_select(select){
    var mashup_id = $(select).val();

    console.log(mashup_id);

    document.getElementById('configuration').src = '/mashup-objects/'+mashup_id+'/'+$componentID;



    
}

</script>



<form method='post'>
    <input type='hidden' name='_token' value='{{csrf_token()}}' />
    <input type='hidden' name='componentid' value='{{$componentID}}' />
    <div class="form-group">
     


    <div class="form-group">
        <label>Mashup</label>
        <select onchange="create_object_select(this)" class='form-control' required name='config[mashups]'>
            <option value='0'>Choose Mashup</option>
            @foreach($mashups as $m)
            <!-- option with selected  -->
            @if(isset($config) && $m->id == $config->mashups)
            <option selected value='{{$m->id}}'>{{$m->mashupname}}</option>
            @else
            <option  value='{{$m->id}}'>{{$m->mashupname}}</option>
            @endif
            @endforeach
        </select>
    </div>

<div class="form-group">
        <label>Mashup</label>
        <select  class='form-control' required name='config[object]'>
            <option value='0'>Choose Object</option>

        </select>
    </div>



</form>
@elseif($command=='showFunction')
<?php
    if ($key == 'sql') {
        try {
            $sessions = Session::all();
            foreach ($sessions as $key => $val) {
                if (gettype($val) == gettype($value)) {
                    $value = str_replace("[".$key."]", $val, $value);
                }
                
            }
            echo reset(DB::select(DB::raw($value))[0]);
        } catch (\Exception $e) {
            echo 'ERROR';
        }
    } else {
        echo $value;
    }

    ?>
@endif