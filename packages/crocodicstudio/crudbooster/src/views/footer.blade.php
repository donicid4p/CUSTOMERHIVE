<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-{{ trans('crudbooster.right') }} hidden-xs">
        {{ trans('crudbooster.powered_by') }} Data4Prime  | <a id="license" href="">{{ trans('crudbooster.license') }}</a>
    </div>
    <div style="margin-right:15px;" class="pull-{{ trans('crudbooster.right') }} hidden-xs">
        {{Session::get('appname')}} {{ config('app.version') }}
    </div>
    <!-- Default to the left -->
    <strong>{{ trans('crudbooster.copyright') }} &copy; <?php echo date('Y') ?>. {{ trans('crudbooster.all_rights_reserved') }} .</strong>
</footer>







