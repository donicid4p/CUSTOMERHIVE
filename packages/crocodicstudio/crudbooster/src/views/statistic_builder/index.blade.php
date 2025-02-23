@php 
/*
$routeCollection = Illuminate\Support\Facades\Route::getRoutes();




foreach($routeCollection as $key => $value) {

    $action = $value->getAction('controller');
    $controller = class_basename($action); 


    if (
            !empty($controller) && 
            strpos($controller, 'post') === false && 
            strpos($controller, 'Detail') === false && 
            strpos($controller, 'Edit') === false && 
            strpos($controller, 'Delete') === false &&
        ) {
            if (strpos($controller, 'Admin') !== false && strpos($controller, 'Controller') !== false) {
                echo $controller.'<br>';
            }
    }

    



}

*/



@endphp

@push('bottom')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ajaxStart(function () {
            $('.btn-save-statistic').html("<i class='fa fa-spin fa-spinner'></i>");
        })
        $(document).ajaxStop(function () {
            $('.btn-save-statistic').html("<i class='fa fa-save'></i> Auto Save Ready");
        })

        $('.btn-show-sidebar').click(function (e) {
            e.stopPropagation();
        })
        $('html,body').click(function () {
            $('.control-sidebar').removeClass('control-sidebar-open');
        })
    })
</script>
@endpush
@push('head')
<style type="text/css">
.statistic-row>div {

/*overflow: auto;*/

}
    .control-sidebar ul {
        padding: 0 0 0 0;
        margin: 0 0 0 0;
        list-style-type: none;
    }

    .control-sidebar ul li {
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid #555555;
    }

    .control-sidebar ul li:hover {
        background: #555555;
    }

    .control-sidebar ul li .title {
        text-align: center;
        color: #ffffff;
    }

    .control-sidebar ul li img {
        width: 100%;
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }
</style>
@endpush

@push('bottom')
<!-- ADDITION FUNCTION FOR BUTTON -->
<script type="text/javascript">
    var id_cms_statistics = '{{$id_cms_statistics}}';

    function addWidget(id_cms_statistics, area, component) {
        console.log("Add Widget " + component + " to " + area);
        var id = new Date().getTime();
        $('#' + area).append("<div id='" + id + "' class='area-loading'><i class='fa fa-spin fa-spinner'></i></div>");

        var sorting = $('#' + area + ' .border-box').length;
        $.post("{{CRUDBooster::mainpath('add-component')}}", {
            component_name: component,
            id_cms_statistics: id_cms_statistics,
            sorting: sorting,
            area: area
        }, function (response) {
            $('#' + area).append(response.layout);
            $('#' + id).remove();
        })
    }

</script>
<!--DATATABLE-->
<link rel="stylesheet"
    href="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.css')}}">
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset ('vendor/crudbooster/assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<!--END HERE-->
@endpush


@push('head')
<!-- jQuery UI 1.11.4 -->
<style type="text/css">
    .sort-highlight {
        border: 3px dashed #cccccc;
    }

    .layout-grid {
        border: 1px dashed #cccccc;
        min-height: 150px;
    }

    .layout-grid+.layout-grid {
        border-left: 1px dashed transparent;
    }

    .border-box {
        position: relative;
    }

    .border-box .action {
        font-size: 20px;
        display: none;
        text-align: center;
        display: none;
        padding: 3px 5px 3px 5px;
        background: #DD4B39;
        color: #ffffff;
        width: 70px;
        -webkit-border-bottom-right-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-bottomright: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
        position: absolute;
        margin-top: -20px;
        right: 0;
        z-index: 999;
        opacity: 0.8;
    }

    .border-box .action a {
        color: #ffffff;
    }

    .border-box:hover {
        /*border:2px dotted #BC3F30;*/
    }

    @if(CRUDBooster::getCurrentMethod()=='getBuilder') 
        .border-box:hover .action {
        display: block;
    }

    .card {
        margin-bottom: 0px;
    }

    .card-header,
    .inner-box,
    .box-header mb-3,
    .btn-add-widget {
        cursor: move;
    }

    @endif .connectedSortable {
        position: relative;
    }

    .area-loading {
        position: relative;
        width: 100%;
        height: 130px;
        background: #dedede;
        border: 4px dashed #cccccc;
        font-size: 50px;
        color: #aaaaaa;
        margin-bottom: 20px;
    }

    .area-loading i {
        position: absolute;
        left: 45%;
        top: 30%;
        transform: translate(-50%, -50%);
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

@push('bottom')
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(function () {

        var cloneSidebar = $('.control-sidebar').clone();

        @if (CRUDBooster::getCurrentMethod() == 'getBuilder')
    createSortable();

    @endif

    function createSortable() {
        $(".connectedSortable").sortable({
            placeholder: "sort-highlight",
            connectWith: ".connectedSortable",
            handle: ".card-header, .inner-box, .box-header mb-3, .btn-add-widget",
            forcePlaceholderSize: true,
            zIndex: 999999,
            stop: function (event, ui) {
                console.log(ui.item.attr('class'));
                var className = ui.item.attr('class');
                var idName = ui.item.attr('id');
                if (className == 'button-widget-area') {
                    var areaname = $('#' + idName).parent('.connectedSortable').attr('id');
                    var component = $('#' + idName + ' > a').data('component');
                    console.log(areaname);
                    $('#' + idName).remove();
                    addWidget(id_cms_statistics, areaname, component);
                    $('.control-sidebar').html(cloneSidebar);
                    cloneSidebar = $('.control-sidebar').clone();

                    createSortable();
                }
            },
            update: function (event, ui) {
                if (ui.sender) {
                    var componentID = ui.item.attr('id');
                    var areaname = $('#' + componentID).parent('.connectedSortable').attr("id");
                    var index = $('#' + componentID).index();


                    $.post("{{CRUDBooster::mainpath('update-area-component')}}", {
                        componentid: componentID,
                        sorting: index,
                        areaname: areaname
                    }, function (response) {

                    })
                }
            }
        });
    }

        })

</script>

<script type="text/javascript">
    $(function () {

        $('.connectedSortable').each(function () {
            var areaname = $(this).attr('id');

            $.get("{{CRUDBooster::adminpath('statistic_builder/list-component')}}/" + id_cms_statistics + "/" + areaname, function (response) {
                if (response.components) {

                    $.each(response.components, function (i, obj) {
                        $('#' + areaname).append("<div id='area-loading-" + obj.componentID + "' class='area-loading'><i class='fa fa-spin fa-spinner'></i></div>");
                        $.get("{{CRUDBooster::adminpath('statistic_builder/view-component')}}/" + obj.componentID, function (view) {
                            console.log('View For CID ' + view.componentID);
                            $('#area-loading-' + obj.componentID).remove();
                            $('#' + areaname).append(view.layout);

                        })
                    })
                }
            })
        })


        $(document).on('click', '.btn-delete-component', function () {
            var componentID = $(this).data('componentid');
            var $this = $(this);

            swal({
                title: "{{ __('crudbooster.delete_title_confirm') }}",
                text: "{{ __('crudbooster.you_will_not_be_able_to_recover_this_widget') }} !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ __('crudbooster.confirmation_yes') }}",
                closeOnConfirm: true
            },
                function () {

                    $.get("{{CRUDBooster::mainpath('delete-component')}}/" + componentID, function () {
                        $this.parents('.border-box').remove();

                    });
                });

        })
        $(document).on('click', '.btn-edit-component', function () {
            var componentID = $(this).data('componentid');
            var name = $(this).data('name');

            $('#modal-statistic .modal-title').text(name);
            $('#modal-statistic .modal-body').html("<i class='fa fa-spin fa-spinner'></i> Please wait loading...");
            $('#modal-statistic').modal('show');

            $.get("{{CRUDBooster::mainpath('edit-component')}}/" + componentID, function (response) {
                $('#modal-statistic .modal-body').html(response);
            })
        })

        $('#modal-statistic .btn-submit').click(function () {

            $('#modal-statistic form .has-error').removeClass('has-error');

            var required_input = [];
            $('#modal-statistic form').find('input[required],textarea[required],select[required]').each(function () {
                var $input = $(this);
                var $form_group = $input.parent('.mb-3 row');
                var value = $input.val();

                if (value == '') {
                    required_input.push($input.attr('name'));
                }
            })

            if (required_input.length) {
                setTimeout(function () {
                    $.each(required_input, function (i, name) {
                        $('#modal-statistic form').find('input[name="' + name + '"],textarea[name="' + name + '"],select[name="' + name + '"]').parent('.mb-3 row').addClass('has-error');
                    })
                }, 200);

                return false;
            }

            var $button = $(this).text('Saving...').addClass('disabled');

            $.ajax({
                data: $('#modal-statistic form').serialize(),
                type: 'POST',
                url: "{{CRUDBooster::mainpath('save-component')}}",
                success: function () {

                    $button.removeClass('disabled').text('Save Changes');
                    $('#modal-statistic').modal('hide');
                    window.location.href = "{{Request::fullUrl()}}";
                },
                error: function () {
                    alert('Sorry something went wrong !');
                    $button.removeClass('disabled').text('Save Changes');
                }
            })
        })

        // in Enter is pressed
        $('#modal-statistic').on('keypress', 'input', function (e) {
            if (e.which == 13) {
                $('#modal-statistic .btn-submit').click();
            }
        })
    })
</script>
@endpush


<div id='modal-statistic' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="justify-content: space-between;">
                
                <h4 class="modal-title">Modal title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-submit btn btn-primary" data-bs-loading-text="Saving..."
                    autocomplete="off">Save changes</button>
            </div>
        </div>

<!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id='statistic-area'>

@if (!empty($code_layout)) 
    @php echo  str_replace("", "", $code_layout) @endphp
@else 

<div class="statistic-row row">
        <div id='area1' class="col-sm-3 connectedSortable">

        </div>
        <div id='area2' class="col-sm-3 connectedSortable">

        </div>
        <div id='area3' class="col-sm-3 connectedSortable">

        </div>
        <div id='area4' class="col-sm-3 connectedSortable">

        </div>
    </div>

<div class="statistic-row row">
        <div id='area5' class="col-sm-3 connectedSortable">

        </div>
        <div id='area6' class="col-sm-3 connectedSortable">

        </div>
        <div id='area7' class="col-sm-3 connectedSortable">

        </div>
        <div id='area8' class="col-sm-3 connectedSortable">

        </div>
    </div>

    <div class='statistic-row row'>
        <div id='area9' class="col-sm-12 connectedSortable">

        </div>
</div>
@endif
    </div>

</div><!--END STATISTIC AREA-->

<script defer>

if (window.location.href.includes('statistic_builder/builder')) {

    //selectd td elements with id containing 'area'
    const areas = document.querySelectorAll('td[id^="area"]');

    areas.forEach(area => {
        area.style.border = '1px solid black';
    });

}

</script>
