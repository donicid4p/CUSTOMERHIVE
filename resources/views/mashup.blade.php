@php

use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

$conf = QlikMashupController::getConf($qlik_conf);
$token = HelpersQlikHelper::getJWTToken(CRUDBooster::myId(), $conf->id);

@endphp 
@if (isset($conf) && $conf) 
<script type="text/javascript"  src="{{$conf->host}}/resources/js/external/requirejs/require.js{{$param}}"></script>
<!--<script type="text/javascript"  src="{{$conf->host}}/resources/assets/external/requirejs/require.js{{$param}}"></script>-->
<link rel="stylesheet" href="{{$conf->host}}/resources/autogenerated/qlik-styles.css">

<style>


</style>


<div id="{{$mashup->appid}}" class="small-box [color]">
<script  type="text/javascript" >

var qlik_token = '{{$token}}';
var host = '{{$conf->host}}';
var prefix = '{{$conf->prefix}}';
var port = '{{$conf->port}}';
var webIntegrationId = '{{$conf->webIntegrationId}}';
var appId = '{{$mashup->appid}}';
var componentID = '{{$componentID}}';
var objectid = '{{$mashups->object}}';



</script>


  <div id="title">Loading Qlik App. Please wait.</div>

<div id="{{$mashups->object}}"></div>

<div class="text-danger" ></div>
</div>
<script defer  src="{{asset('js/qlik_login_widget.js')}}"></script>
@endif
