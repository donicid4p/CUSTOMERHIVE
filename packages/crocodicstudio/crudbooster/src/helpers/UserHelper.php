<?php
namespace crocodicstudio\crudbooster\helpers;

use \App\User;
use \App\Group;
use \App\Tenant;
use \App\UsersGroup;

class UserHelper  {

  /**
  *	get current user's primary group
  *
  * @return int id of the group
  */
  public static function current_user_primary_group() {
    $user_id = CRUDBooster::myId();
    $user = User::find($user_id);
    return $user->primary_group;
  }

  /**
  *	get current user's primary group
  *
  * @return int id of the group
  */
  public static function current_user_primary_group_name() {
    $user_id = CRUDBooster::myId();
    $user = User::find($user_id);
    return $user->primary_group()->name;
  }

  /**
  *	get current user's tenant
  *
  * @return int id of the tenant
  */
  public static function current_user_tenant() {
    $user_id = CRUDBooster::myId();
    $user = User::find($user_id);
    return $user->tenant;
  }

  /**
  *	get current user's groups
  *
  * @return array[int] list of the group's ids of which current user is a member
  */
  public static function current_user_groups() {
    $user_id = CRUDBooster::myId();
    $groups = UsersGroup::where('user_id',$user_id)->pluck('group_id')->all();
    $primary_group = self::current_user_primary_group();
    if(!in_array($primary_group, $groups)){
      array_push($groups, $primary_group);
    }
    return $groups;
  }

  /**
  *	get current user's groups
  *
  * @return array[string] list of the group's names of which current user is a member
  */
  public static function current_user_allowed_groups_names() {
    if(CRUDBooster::isSuperadmin()){
      return Group::pluck('name')->toArray();
    }
    else{
      $groups = UserHelper::current_user_groups();
      foreach ($groups as $key => $value) {
        $result[] = Group::find($value)->name;
      }
      return $result;
    }
  }
}
