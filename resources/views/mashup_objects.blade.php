
@php 

use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

$conf = QlikMashupController::getConf($qlik_conf);
//generate a random string
$state = bin2hex(random_bytes(16));


if ($conf->type == 'SAAS') {
  $token = HelpersQlikHelper::getJWTToken(CRUDBooster::myId(), $conf->id);
  $src = $conf->host . "/resources/assets/external/requirejs/require.js?state=$state";
  $css = $conf->host . "/resources/autogenerated/qlik-styles.css";
} else {

  //in realta sarebbe il ticket
  $token = HelpersQlikHelper::getTicketFromConf($conf->id) ;

  $token2 = HelpersQlikHelper::getTicketFromConf($conf->id) ;
$token3 = HelpersQlikHelper::getTicketFromConf($conf->id) ;
$token4 = HelpersQlikHelper::getTicketFromConf($conf->id) ;
  $data_ticket = HelpersQlikHelper::dataForTicketConf($conf->id);
  //$src = $conf->host . "/resources/assets/external/requirejs/require.js?state=$state&qlikTicket=".$token2;
  //$src = $conf->host . "/resources/assets/external/requirejs/require.js?state=$state";
  $src = $conf->host . "/resources/assets/external/requirejs/require.js?qlikTicket=".$token2;
  $css = $conf->host . "/resources/autogenerated/qlik-styles.css?qlikTicket=".$token;
}

$js_defer = $conf->type == "On-Premise" ? "js/qlik_login_widget_objop.js" : "js/qlik_login_widget_obj.js";

$param = $conf->type == 'On-Premise' ? "?qlikTicket=$token" : "";

@endphp 
@if (isset($conf) && $conf) 
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="{{ $css }}">

<script type="text/javascript"  src="{{$src}}"></script>

</head>

<body>
<img src="https://qse.datasynapsi.cloud/resources/hub/img/core/logo/Qlik-Logo_RGB.svg?qlikTicket={{$token3}}" alt="Qlik Logo" style="width: 200px; height: 100px;">
  <div id="{{$mashup->appid}}" >
<script  type="text/javascript" >

var token4 = '{{$token4}}';
console.log("token4");
console.log(token4);

console.log("css");
console.log("{{$css}}");

console.log("src");
console.log("{{$src}}");

var type = '{{$conf->type}}';
console.log("type");
console.log(type);

var qlik_token = '{{$conf->type == "SAAS" ? $token : ""}}';
console.log("qlik_token");
console.log(qlik_token);

var qlik_ticket = '{{$conf->type == "On-Premise" ? $token : ""}}';
console.log("qlik_ticket");
console.log(qlik_ticket);

var src_js = "@php echo $src  @endphp";
console.log("src_js");
console.log(src_js);

@if ($conf->type == 'On-Premise')

var ticket_data = @php echo isset($data_ticket) ? $data_ticket : "" @endphp;
console.log("ticket_data");
console.log(ticket_data);

@endif


var host = '{{$conf->host}}';
console.log("host");
console.log(host);

var prefix = '{{$conf->prefix}}';
console.log("prefix");
console.log(prefix);


var port = '{{$conf->port}}';
console.log("port");
console.log(port);

var webIntegrationId = '{{$conf->webIntegrationId}}';

console.log("webIntegrationId");
console.log(webIntegrationId);
var appId = '{{$mashup->appid}}';
console.log("appId");
console.log(appId);

var mashupId = '{{$mashup->id}}';
console.log("mashupId");
console.log(mashupId);

var objectid = '{{$config->object}}';
console.log("objectid");
console.log(objectid);

var hidden_object = parent.document.getElementById('mashup_object_hidden');
var hidden_app = parent.document.getElementById('mashup_app_hidden');


</script>

  <div id="title">Loading Qlik App. Please wait.</div>

<input type="hidden" id="state_page" name="state_page">
<script defer  src="{{asset($js_defer)}}"></script>
@endif
</body>


</html>

