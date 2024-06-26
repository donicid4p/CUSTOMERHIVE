
@php 

use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

$conf = QlikMashupController::getConf($qlik_conf);
$token = HelpersQlikHelper::getJWTToken(CRUDBooster::myId(), $conf->id);


@endphp 
@if (isset($conf) && $conf) 
<script type="text/javascript"  src="{{$conf->host}}/resources/assets/external/requirejs/require.js"></script>
<link rel="stylesheet" href="{{$conf->host}}/resources/autogenerated/qlik-styles.css">



<div id="{{$mashup->appid}}" >
<script  type="text/javascript" >

var qlik_token = '{{$token}}';
var host = '{{$conf->host}}';
var prefix = '{{$conf->prefix}}';
var port = '{{$conf->port}}';
var webIntegrationId = '{{$conf->webIntegrationId}}';
var appId = '{{$mashup->appid}}';
var mashupId = '{{$mashup->id}}';
var objectid = '{{$config->object}}';

var hidden_object = parent.document.getElementById('mashup_object_hidden');
var hidden_app = parent.document.getElementById('mashup_app_hidden');

/*
console.log("hidden_object: " + hidden_object.value);
console.log("hidden_app: " + hidden_app.value);
console.log("objectid: "+objectid);
console.log("mashupId: "+mashupId);
*/

</script>

  <div id="title">Loading Qlik App. Please wait.</div>

<input type="hidden" id="state_page" name="state_page">
<script   src="{{asset('js/qlik_login_widget_obj.js')}}"></script>
@endif

