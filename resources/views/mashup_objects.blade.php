
@php 

use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

$conf = QlikMashupController::getConf($qlik_conf);
//generate a random string
$state = bin2hex(random_bytes(16));

$token = '';
$src = '';
$css = '';

if ($conf->type == 'SAAS') {
  $token = HelpersQlikHelper::getJWTToken(CRUDBooster::myId(), $conf->id);
  $src = $conf->host . "/resources/assets/external/requirejs/require.js?state=$state";
  $css = $conf->host . "/resources/autogenerated/qlik-styles.css";
} 


if ($conf->type == 'On-Premise' && $conf->auth == 'JWT') {
  $token = HelpersQlikHelper::getJWTTokenOP(CRUDBooster::myId(), $conf->id);
  $src = $conf->host . "/resources/assets/external/requirejs/require.js?state=$state";
  $css = $conf->host . "/resources/autogenerated/qlik-styles.css";
  //$src = $conf->host . "/jwt/resources/assets/external/requirejs/require.js?state=$state";
  //$css = $conf->host . "/jwt/resources/autogenerated/qlik-styles.css";
  
} 


if ($conf->type == 'On-Premise' && $conf->auth == 'Ticket') {

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

$js_defer = $conf->type == "On-Premise" ? "js/qlik_login_widget_objop" : "js/qlik_login_widget_obj";

$param = $conf->type == 'On-Premise' ? "?qlikTicket=$token" : "";

@endphp 
@if (isset($conf) && $conf) 
<!DOCTYPE html>
<html>
<head>
<script  type="text/javascript" >


console.log("css");
console.log("{{$css}}");

console.log("src");
console.log("{{$src}}");

var type = '{{$conf->type}}';
console.log("type");
console.log(type);

var qlik_token = '{{$conf->auth == "JWT" ? $token : ""}}';
console.log("qlik_token");
console.log(qlik_token);
@if ($conf->type == 'On-Premise' && $conf->auth == "Ticket")
var qlik_ticket = '{{$conf->type == "On-Premise" && $conf->auth == "Ticket" ? $token : ""}}';
console.log("qlik_ticket");
console.log(qlik_ticket);
@endif

var src_js = "@php echo $src  @endphp";
console.log("src_js");
console.log(src_js);

@if ($conf->type == 'On-Premise' && $conf->auth == "Ticket")

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
<script type="text/javascript"  src="{{asset($js_defer)}}.js"></script>

<!--<link rel="stylesheet" href="{{ $css }}">-->


<!--



<script  type="text/javascript"  src="{{asset($js_defer)}}2.js"></script>
-->

</head>

<body>

  <div id="{{$mashup->appid}}" >


  <div id="title">Loading Qlik App. Please wait.</div>

<input type="hidden" id="state_page" name="state_page">





@endif
</body>
<!--<script defer type="text/javascript"   src="{{$src}}"></script>-->
<!--<script type="text/javascript" defer  src="{{$src}}"></script>-->


<!--<script defer type="text/javascript"  src="{{asset($js_defer)}}2.js"></script>-->


</html>

