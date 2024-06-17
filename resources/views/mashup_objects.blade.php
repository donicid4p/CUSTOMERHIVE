
@php 

use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;

$token = HelpersQlikHelper::getJWTToken(1, 3);


$conf = QlikMashupController::getConf($qlik_conf);


@endphp 
@if (isset($conf) && $conf) 
<script type="text/javascript"  src="{{$conf->host}}/resources/assets/external/requirejs/require.js"></script>
<link rel="stylesheet" href="{{$conf->host}}/resources/autogenerated/qlik-styles.css">



<div id="{{$mashup->appid}}" class="small-box [color]">
<script  type="text/javascript" >

var qlik_token = '{{$token}}';
var host = '{{$conf->host}}';
var prefix = '{{$conf->prefix}}';
var port = '{{$conf->port}}';
var webIntegrationId = '{{$conf->webIntegrationId}}';
var appId = '{{$mashup->appid}}';
console.log(appId);
</script>

  <div id="title"></div>

<div id="currentselection"></div>

<div class="text-danger" ></div>
</div>
<input type="hidden" name="state_page">
<script  src="{{asset('js/qlik_login_widget.js')}}"></script>
@endif

