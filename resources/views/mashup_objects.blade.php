
@php 

use crocodicstudio\crudbooster\helpers\QlikHelper as HelpersQlikHelper;
use crocodicstudio\crudbooster\controllers\QlikMashupController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;

$conf = QlikMashupController::getConf($qlik_conf);
//dd($conf);

if ($conf->type == 'SAAS') {
  $token = HelpersQlikHelper::getJWTToken(CRUDBooster::myId(), $conf->id);
  $src = $conf->host . "/resources/assets/external/requirejs/require.js";
  $css = $conf->host . "/resources/autogenerated/qlik-styles.css";
} else {

  //in realta sarebbe il ticket
  $token = HelpersQlikHelper::getTicketFromConf($conf->id) ;
  $data_ticket = HelpersQlikHelper::dataForTicketConf($conf->id);
  $src = $conf->host . "/resources/hub/external/requirejs/require.js?qlikTicket=".$token;
  $css = $conf->host . "/resources/autogenerated/qlik-styles.css?qlikTicket=".$token;
}

$js_defer = $conf->type == "On-Premise" ? "js/qlik_login_widget_objop.js" : "js/qlik_login_widget_obj.js";

$param = $conf->type == 'On-Premise' ? "?qlikTicket=$token" : "";

@endphp 
@if (isset($conf) && $conf) 

<link rel="stylesheet" href="{{ $css }}">

<script type="text/javascript"  src="{{$src}}"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>-->
                                                    




<div id="{{$mashup->appid}}" >
<script  type="text/javascript" >

var type = '{{$conf->type}}';

var qlik_token = '{{$conf->type == "SAAS" ? $token : ""}}';
var qlik_ticket = '{{$conf->type == "On-Premise" ? $token : ""}}';

var ticket_data = @php echo isset($data_ticket) ? $data_ticket : null @endphp;
console.log(ticket_data);

var host = '{{$conf->host}}';
var prefix = '{{$conf->prefix}}';
var port = '{{$conf->port}}';
var webIntegrationId = '{{$conf->webIntegrationId}}';
var appId = '{{$mashup->appid}}';
var mashupId = '{{$mashup->id}}';
var objectid = '{{$config->object}}';

var hidden_object = parent.document.getElementById('mashup_object_hidden');
var hidden_app = parent.document.getElementById('mashup_app_hidden');



</script>

  <div id="title">Loading Qlik App. Please wait.</div>

<input type="hidden" id="state_page" name="state_page">
<script defer  src="{{asset($js_defer)}}"></script>
@endif

