<?php

namespace crocodicstudio\crudbooster\helpers;

use Session;
use Request;
use Schema;
use Cache;
use DB;
use Route;
use Validator;


class ModuleHelperHelper
{

  public static function getUrl($mod) {

    if (isset($mod->id)) {
        $helper = DB::table('module_helpers')->where('id_cms_moduls', $mod->id)->first();
    }

    if (isset($helper->url)) {
        return $helper->url;
    }

    return "";

  }


  public static function getUrlCV($mod, $id) {

    if (isset($mod->id)) {
        $helper = DB::table($mod->table_name)->where('id', $id)->first();
    }

    if (isset($helper->url_help)) {
        return $helper->url_help;
    }

    return "";

  }

}
