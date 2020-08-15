<?php
namespace crocodicstudio\crudbooster\helpers;

use Illuminate\Support\Facades\DB;

class MenuHelper  {

  /**
  *	Get menus
  *
  * @param boolean 1 to load active menus or 0 to load inactive menus
  *
  * @return array struttura del menu
  */
  public static function get_menu($is_active) {

    $menu_list = DB::table('cms_menus')
                        ->where('parent_id', 0)//menu di primo livello o parent, che non sono figli di un altro menu
                        ->where('is_active', $is_active)
                        ->orderby('sorting', 'asc')
                        ->get();

    if(!CRUDBooster::isSuperadmin())
    {
      //tenant admin vede nella lista solo le voci di menu del proprio tenant
      $menu_list = $menu_list->where('tenant',UserHelper::current_user_tenant());
    }

    foreach ($menu_list as &$menu) {
      $children = DB::table('cms_menus')
                  ->where('is_active', $is_active)
                  ->where('parent_id', $menu->id)
                  ->orderby('sorting', 'asc')
                  ->get();

      foreach ($children as $child) {
        $grandchild = DB::table('cms_menus')
                    ->where('is_active', $is_active)
                    ->where('parent_id', $child->id)
                    ->orderby('sorting', 'asc')
                    ->get();
        if (count($grandchild)) {
          $child->children = $grandchild;
        }
      }
      $menu->children = $children;
    }
    return $menu_list;
  }

}
