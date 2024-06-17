


@php 


use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;

$token = HelpersQlikHelper::getJWTToken(1, 3);

$conf = QlikMashupController::getConf($qlik_conf);


@endphp 
@if (isset($conf) && $conf) 
<script type="text/javascript"  src="{{$conf->host}}/resources/assets/external/requirejs/require.js"></script>
<link rel="stylesheet" href="{{$conf->host}}/resources/autogenerated/qlik-styles.css">

<style>
/*
.qv-object-com-qliktech-horizlist .qv-object-content {
    overflow: auto;
}
.qv-object-com-qliktech-horizlist ul {
    list-style: none;
}
.qv-object-com-qliktech-horizlist li.data {
    display: inline-block;
    margin: 3px;
    padding: 4px;
    border-top: 1px solid #ddd;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #111;
    border-right: 1px solid #111;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
*/


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
var objectid = '{{$objectid}}';

</script>





    
  <div id="title"></div>

<div id="currentselection"></div>

<div class="text-danger" ></div>
</div>
<script defer  src="{{asset('js/qlik_login_widget_obj.js')}}"></script>
@endif
<!-- 
@if(isset($componentID))
	<h1>{{$componentID}}</h1>
@else 
	<h1>Component ID not found</h1>
@endif


@if(isset($token))
	<h1>{{$token}}</h1>
@else
	<h1>Token not found</h1>
@endif
-->


