<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDbooster;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {


	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';
		$this->button_import 	   = FALSE;
		$this->button_export 	   = FALSE;
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"User directory","name"=>"user_directory");
		$this->col[] = array("label"=>"Qlik login","name"=>"qlik_login");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array();
		$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|alpha_spaces|min:3');
		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId());
		$this->form[] = array("label"=>"User directory","name"=>"user_directory",'required'=>false,'validation'=>'min:3');
		$this->form[] = array("label"=>"Qlik login","name"=>"qlik_login",'required'=>false,'validation'=>'min:3');
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'required'=>false,'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90);
		$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true);
		// $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
		$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
		$this->form[] = array("label"=>"Password Confirmation","name"=>"password_confirmation","type"=>"password","help"=>"Please leave empty if not change");
		# END FORM DO NOT REMOVE THIS LINE

		$this->addaction = array();
		$this->addaction[] = ['label'=>'','url'=>CRUDBooster::mainpath('groups/[id]'),'icon'=>'fa fa-users','color'=>'info','title'=>'View groups'];
	}

	public function getProfile() {

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());
		$this->cbView('crudbooster::default.form',$data);
	}
	public function hook_before_edit(&$postdata,$id) {
		unset($postdata['password_confirmation']);
	}
	public function hook_before_add(&$postdata) {
	    unset($postdata['password_confirmation']);
	}

	public function groups($user_id){
		//check auth
		if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}

		$data = [];
		$data['groups'] = DB::table('users_groups')
												->where('users_groups.user_id',$user_id)
												->join('groups', 'groups.id', '=', 'users_groups.group_id')
												->select('groups.id', 'groups.name', 'groups.description')
												->get();

		$data['user'] = \App\User::find($user_id);
		$data['user_id'] = $user_id;

		$data['page_title'] = $data['user']->name.' groups';

		//add group form
		$data['forms'] = [];
		$data['forms'][] = ['label'=>'Name','name'=>'name','type'=>'user_groups_datamodal','width'=>'col-sm-6','datamodal_table'=>'groups','datamodal_where'=>'','datamodal_columns'=>'name','datamodal_columns_alias'=>'Name','datamodal_select_to'=>$user_id,'required'=>true];
		$data['forms'][] = ['label'=>'Description','name'=>'description','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-6','placeholder'=>'Group description','readonly'=>true];
		$data['action'] = CRUDBooster::mainpath($user_id."/add_group");
		$data['return_url'] = CRUDBooster::mainpath('groups/'.$user_id);

		$this->cbView('users.groups',$data);
	}

	public function add_group($user_id){
		//check auth update su groups
		//TODO creaiamo permesso specifico da autorizzare e controllare per group membership?
		if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}
		$group_id = $_POST['name'];
		$return_url = $_POST['return_url'];
		$ref_mainpath = $_POST['ref_mainpath'];

		//check if user is already in group
		$membership = \App\UsersGroup::where('group_id',$group_id)
																->where('user_id',$user_id)
																->count();

		if($membership == 0){
			$add_member = new \App\UsersGroup;
			$add_member->group_id = $group_id;
			$add_member->user_id = $user_id;
			$add_member->save();
		}

		//redirect
		if(empty($return_url)){
				$return_url = $ref_mainpath;
		}
		return redirect($return_url);
	}

	public function remove_group($user_id, $group_id){
		//check auth update su groups
		//TODO creaiamo permesso specifico da autorizzare e controllare per group membership?
		if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}

		//check if group_id and user_id are int
		if(filter_var($group_id, FILTER_VALIDATE_INT) === false OR filter_var($user_id, FILTER_VALIDATE_INT) === false) {
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}

		$data['delete'] = DB::table('users_groups')
												->where('group_id',$group_id)
												->where('user_id',$user_id)
												->delete();

		return redirect('admin/users/groups/'.$user_id);
	}
}
