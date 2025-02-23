<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
        use App\Http\Controllers\AdminKnowledgeBaseController;

		class ApiKnowledgebaseConfirmController extends \crocodicstudio\crudbooster\controllers\ApiController {
            public $controller = null;
		    function __construct() {
				$this->table       = "mg_knowledgebase";
				$this->permalink   = "knowledgebase_confirm";
				$this->method_type = "post";
                $this->controller = new AdminKnowledgeBaseController();
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}