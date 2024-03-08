@extends('crudbooster::admin_template',['target_layout' => isset($row->target_layout) ? $row->target_layout : null ])

@if(isset($row->target_layout) && $row->target_layout == 2)
<!-- fill content settings -->
@section('content')
<div class="qi_iframe_container">
  {{ $debug == 'Active' ? $item_url : '' }}
  <iframe class="qi_iframe" src=""  style="border:none;"></iframe>
</div>
@endsection
@else
<!-- default -->
@section('content')
<div class="box qi_box">
  <h4 class="qi_subtitle">{{ $subtitle }}</h4>
  {{ $debug == 'Active' ? $item_url : '' }}
  <div class="qi_iframe_container">
    <iframe class="qi_iframe" src=""  style="border:none;"></iframe>
  </div>
</div>
@endsection
@endif

@push('bottom')
<script type="text/javascript">
  $(document).ready(function () {
    //aggiungi icona al titolo delle pagine iframe qlik
    var menu_item = $('li.active').first();
    if (menu_item.hasClass('treeview')) {
      //prendi icona dal child
      icon = $('li.active:not(.treeview):first i')[0].className;
    }
    else {
      //prendi icona
      if ($('li.active i')[0]) {
        icon = $('li.active i')[0].className;
      }
      else {
        icon = '';
      }
    }
    $('#title_icon').addClass('fa ' + icon);

    if ($('#title_icon').hasClass('qlik_icon')) {
      //prendi icona dal child
      var qlik_logo = '<img class="qlik_logo" src=/images/qlik_logo.png />';
      $(qlik_logo).insertBefore($('#title_icon'));
    }

  })
</script>

@endpush
<script>
  const TENANT = '{{ $tenant }}';

  const WEBINTEGRATIONID = '{{ $web_int_id }}';
  const APPID = '##APP##';
  const JWTTOKEN = "{{ $token}}";
</script>
<script defer src="{{asset('js/qliksaas_login.js')}}"></script>
@push('head')
<style>
  /*set iframe size*/
  .qi_iframe {
    width: {
        {
        $row->frame_width
      }
    }

    !important;

    height: {
        {
        $row->frame_height
      }
    }

    !important;
  }
</style>
@endpush