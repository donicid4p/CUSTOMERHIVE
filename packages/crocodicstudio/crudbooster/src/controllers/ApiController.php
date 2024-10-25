<?php

namespace crocodicstudio\crudbooster\controllers;

use CRUDBooster;
use ModuleHelper;
use UserHelper;
use Session;
use \crocodicstudio\crudbooster\controllers\AdminCmsUsersController;
use App\Http\Controllers\AdminSmartphonesController;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class ApiController extends Controller
{
    public $method_type;
    public $permalink;

    private $hook_api_status;
    private $hook_api_message;
    private $last_id_tmp = [];

    private $limit = 50;
    private $output = null;

    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function output($array)
    {
        $this->output = $array;
    }

    public function hook_before(&$postdata)
    {
    }

    public function hook_after($postdata, &$result)
    {
    }

    public function hook_query(&$query)
    {
    }

    public function hook_api_status($boolean)
    {
        $this->hook_api_status = $boolean;
    }

    public function hook_api_message($message)
    {
        $this->hook_api_message = $message;
    }

    public function execute_api($output = 'JSON')
    {




        // DB::enableQueryLog();

        $debug = [];

        $posts = Request::all();
        $posts_keys = array_keys($posts);
        $posts_values = array_values($posts);

        $row_api = DB::table('cms_apicustom')->where('permalink', $this->permalink)->first();

        $action_type = $row_api->aksi;
        $table = $row_api->tabel;
        $pk = CRUDBooster::pk($table);

        $debug_mode_message = 'You are in debug mode !';

        /*
        | ----------------------------------------------
        | Method Type validation
        | ----------------------------------------------
        |
        */

        if ($row_api->method_type) {
            $method_type = $row_api->method_type;
            if ($method_type) {
                if (!Request::isMethod($method_type)) {
                    $result['api_status'] = 0;
                    $result['api_message'] = "The requested method is not allowed!";
                    goto show;
                }
            }
        }

        $debug[] = 'Method Type is OK';

        /*
        | ----------------------------------------------
        | User validation
        | ----------------------------------------------
        |
        */


        $login = $this->login();

        if ($login != "OK") {
            $result['api_status'] = 0;
            $result['api_message'] = $login;
            goto show;
        }

        $debug[] = 'User is OK';


        /*
        | ----------------------------------------------
        | Check the row is exists or not
        | ----------------------------------------------
        |
        */
        if (!$row_api) {
            $result['api_status'] = 0;
            $result['api_message'] = 'Sorry this API endpoint is no longer available or has been changed. Please make sure endpoint is correct.';

            goto show;
        }

        $debug[] = 'Row API is OK';

        @$parameters = unserialize($row_api->parameters);
        @$responses = unserialize($row_api->responses);


        foreach ($responses as $k => $v) {
            if (empty($v['name']) || $v['used'] != 1) {
                unset($responses[$k]);
            }
        }
        foreach ($parameters as $k => $v) {
            if (empty($v['name']) || $v['used'] != 1) {
                unset($parameters[$k]);
            }
        }

        /*
        | ----------------------------------------------
        | User Data Validation
        | ----------------------------------------------
        |
        */
        $type_except = ['password', 'ref', 'base64_file', 'custom', 'search'];
        if ($parameters) {

            $input_validator = [];
            $data_validation = [];
            foreach ($parameters as $param) {
                $name = $param['name'];
                $type = $param['type'];
                $value = isset($posts[$name]) ? $posts[$name] : '';

                $required = $param['required'];
                $config = $param['config'];
                $used = $param['used'];
                $format_validation = [];

                if ($used && !$required && $value == '') {
                    continue;
                }

                if ($used == '0') {
                    continue;
                }

                if ($config && substr($config, 0, 1) == '*') {
                    continue;
                }

                $input_validator[$name] = trim($value);

                if ($required == '1') {
                    $format_validation[] = 'required';
                }

                if ($type == 'exists') {
                    $config = explode(',', $config);
                    $table_exist = $config[0];
                    $table_exist = CRUDBooster::parseSqlTable($table_exist)['table'];
                    $field_exist = $config[1];
                    $config = ($field_exist) ? $table_exist . ',' . $field_exist : $table_exist;
                    $format_validation[] = 'exists:' . $config;
                } elseif ($type == 'unique') {
                    $config = explode(',', $config);
                    $table_exist = $config[0];
                    $table_exist = CRUDBooster::parseSqlTable($table_exist)['table'];
                    $field_exist = $config[1];
                    $config = ($field_exist) ? $table_exist . ',' . $field_exist : $table_exist;
                    $format_validation[] = 'unique:' . $config;
                } elseif ($type == 'date_format') {
                    $format_validation[] = 'date_format:' . $config;
                } elseif ($type == 'digits_between') {
                    $format_validation[] = 'digits_between:' . $config;
                } elseif ($type == 'in') {
                    $format_validation[] = 'in:' . $config;
                } elseif ($type == 'mimes') {
                    $format_validation[] = 'mimes:' . $config;
                } elseif ($type == 'min') {
                    $format_validation[] = 'min:' . $config;
                } elseif ($type == 'max') {
                    $format_validation[] = 'max:' . $config;
                } elseif ($type == 'not_in') {
                    $format_validation[] = 'not_in:' . $config;
                } elseif ($type == 'image') {
                    $format_validation[] = 'image';
                    $input_validator[$name] = Request::file($name);
                } elseif ($type == 'file') {
                    $format_validation[] = 'file';
                    $input_validator[$name] = Request::file($name);
                } else {
                    if (!in_array($type, $type_except)) {
                        $format_validation[] = $type;
                    }
                }

                if ($name == 'id') {
                    $table_exist = CRUDBooster::parseSqlTable($table)['table'];
                    $table_exist_pk = CRUDBooster::pk($table_exist);
                    $format_validation[] = 'exists:' . $table_exist . ',' . $table_exist_pk;
                }

                if (count($format_validation)) {
                    $data_validation[$name] = implode('|', $format_validation);
                }
            }

            $validator = Validator::make($input_validator, $data_validation);
            if ($validator->fails()) {
                $message = $validator->errors()->all();
                $message = implode(', ', $message);
                $result['api_status'] = 0;
                $result['api_message'] = $message;

                goto show;
            }
        }

        $debug[] = 'User Data Validation is OK';

        $responses_fields = [];
        foreach ($responses as $r) {
            if ($r['used']) {
                $responses_fields[] = $r['name'];
            }
        }

        $debug[] = 'Responses Fields is OK';

        $this->hook_before($posts);
        if ($this->output) {
            return response()->json($this->output);
        }


        $debug[] = 'Hook Before is OK';

        //dd($this);
        $limit = ($this->limit) ?: $posts['limit'];
        $offset = isset($posts['offset']) ?: 0;
        $orderby = isset($posts['orderby']) ?: $table . '.' . $pk . ',desc';
        $uploads_format_candidate = explode(',', config("crudbooster.UPLOAD_TYPES"));
        $uploads_candidate = explode(',', config('crudbooster.IMAGE_FIELDS_CANDIDATE'));
        $password_candidate = explode(',', config('crudbooster.PASSWORD_FIELDS_CANDIDATE'));
        $asset = asset('/');


        unset($posts['limit']);
        unset($posts['offset']);
        unset($posts['orderby']);

        if ($action_type == 'list' || $action_type == 'detail' || $action_type == 'delete') {
            $name_tmp = [];
            $data = DB::table($table);
            if ($offset) {
                $data->skip($offset);
            }
            if ($limit) {
                $data->take($limit);
            }
            //dd($responses);
            foreach ($responses as $resp) {
                $name = $resp['name'];
                $type = $resp['type'];
                $subquery = $resp['subquery'];
                $used = intval($resp['used']);

                if (in_array($type, $type_except)) {
                    continue;
                }

                if ($used == 0 && !CRUDBooster::isForeignKey($name)) {
                    continue;
                }

                if (in_array($name, $name_tmp)) {
                    continue;
                }

                if ($name == 'ref_id') {
                    continue;
                }

                if ($type == 'custom') {
                    continue;
                }

                if ($subquery && $subquery != 'null') {
                    $data->addSelect(DB::raw('(' . $subquery . ') as ' . $name));
                    $name_tmp[] = $name;
                    continue;
                }

                if ($used) {
                    $data->addSelect($table . '.' . $name);
                }

                $name_tmp[] = $name;

                if (CRUDBooster::isForeignKey($name)) {
                    $jointable = CRUDBooster::getTableForeignKey($name);
                    $jointable_field = CRUDBooster::getTableColumns($jointable);
                    $jointablePK = CRUDBooster::pk($jointable);
                    $data->leftjoin($jointable, $jointable . '.' . $jointablePK, '=', $table . '.' . $name);
                    foreach ($jointable_field as $jf) {
                        $jf_alias = $jointable . '_' . $jf;
                        if (in_array($jf_alias, $responses_fields)) {
                            $data->addselect($jointable . '.' . $jf . ' as ' . $jf_alias);
                            $name_tmp[] = $jf_alias;
                        }
                    }
                }
            } //End Responses
            foreach ($parameters as $param) {
                $name = $param['name'];
                $type = $param['type'];
                $value = isset($posts[$name]) ? $posts[$name] : '';
                $used = $param['used'];
                $required = $param['required'];
                $config = $param['config'];

                if ($type == 'password') {
                    $data->addselect($table . '.' . $name);
                }

                if ($type == 'search') {
                    $search_in = explode(',', $config);

                    if ($required == '1') {
                        $data->where(function ($w) use ($search_in, $value) {
                            foreach ($search_in as $k => $field) {
                                if ($k == 0) {
                                    $w->where($field, "like", "%$value%");
                                } else {
                                    $w->orWhere($field, "like", "%$value%");
                                }
                            }
                        });
                    } else {
                        if ($used) {
                            if ($value) {
                                $data->where(function ($w) use ($search_in, $value) {
                                    foreach ($search_in as $k => $field) {
                                        if ($k == 0) {
                                            $w->where($field, "like", "%$value%");
                                        } else {
                                            $w->orWhere($field, "like", "%$value%");
                                        }
                                    }
                                });
                            }
                        }
                    }
                }
            }
            //dd($data->toSql());

            if (CRUDBooster::isColumnExists($table, 'deleted_at')) {
                $data->where($table . '.deleted_at', null);
            }

            $data->where(function ($w) use ($parameters, $posts, $table, $type_except) {
                foreach ($parameters as $param) {
                    $name = $param['name'];
                    $type = $param['type'];
                    $value = isset($posts[$name]) ? $posts[$name] : '';
                    $used = $param['used'];
                    $required = $param['required'];

                    if (in_array($type, $type_except)) {
                        continue;
                    }

                    if ($required == '1') {
                        if (CRUDBooster::isColumnExists($table, $name)) {
                            $w->where($table . '.' . $name, $value);
                        } else {
                            $w->having($name, '=', $value);
                        }
                    } else {
                        if ($used) {
                            if ($value) {
                                if (CRUDBooster::isColumnExists($table, $name)) {
                                    $w->where($table . '.' . $name, $value);
                                } else {
                                    $w->having($name, '=', $value);
                                }
                            }
                        }
                    }
                }
            });

            //IF SQL WHERE IS NOT NULL
            if ($row_api->sql_where) {
                $theSql = $row_api->sql_where;
                //blow it apart at the variables;
                preg_match_all("/\[([^\]]*)\]/", $theSql, $matches);
                foreach ($matches[1] as $match) {
                    foreach ($parameters as $param) {
                        if (in_array($match, $param)) {
                            /* it is possible that the where condition
                             * asks for data that's not required
                             * so we're not going to check for that
                             * it's up to the API creator
                             */
                            $value = $posts[$match];
                            /* any password parameter is invalid by default
                             * if they were hashed by Laravel there's no way to retrieve it
                             * and they're handled later through Auth
                             */
                            if ($param['type'] === 'password') {
                                Log::error('Password parameters cannot be used in WHERE queries');

                                return response()->view('errors.500', [], 500);
                            }
                            $value = "'" . $value . "'";
                            //insert our $value into its place in the WHERE clause
                            $theSql = preg_replace("/\[([^\]]*" . $match . ")\]/", $value, $theSql);
                        }
                    }
                }
                $data->whereraw($theSql);
            }

            $this->hook_query($data);

            $debug[] = 'Data Query is OK';

            //return response()->json($debug, 200);

            if ($action_type == 'list') {
                if ($orderby) {
                    $orderby_raw = explode(',', $orderby);
                    $orderby_col = $orderby_raw[0];
                    $orderby_val = $orderby_raw[1];
                } else {
                    $orderby_col = $table . '.' . $pk;
                    $orderby_val = 'desc';
                }

                $debug[] = $data->orderby($orderby_col, $orderby_val)->toSql();
                //return response()->json($debug, 200);
                $rows = $data->orderby($orderby_col, $orderby_val)->get();

                $debug[] = 'Data Fetch is OK';
                //return response()->json($debug, 200);
                

                if ($rows) {

                    foreach ($rows as $index => &$row) { 

                        $debug[] = 'Before init';
                        return response()->json($this->controller, 200);
                        $this->controller->cbInit();

                        $debug[] = 'After init';
                        //return response()->json($debug, 200);


                        if (!ModuleHelper::can_list($this->controller, $row)) {
                            unset($rows[$index]);
                            continue;
                        }
                        foreach ($row as $k => $v) {
                            $ext = \File::extension($v);
                            if (in_array($ext, $uploads_format_candidate)) {
                                $row->$k = asset($v);
                            }

                            if (!in_array($k, $responses_fields)) {
                                unset($row->$k);
                            }
                        }
                    }

                    $result['api_status'] = 1;
                    $result['api_message'] = 'success';
                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                        $result['api_authorization'] = $debug_mode_message;
                    }
                    $result['data'] = $rows;
                } else {
                    $result['api_status'] = 0;
                    $result['api_message'] = 'There is no data found !';
                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                        $result['api_authorization'] = $debug_mode_message;
                    }
                    $result['data'] = [];
                }
                $debug[] = 'Data Fetch is OK';
                //return response()->json($debug, 200);
            } elseif ($action_type == 'detail') {

                $rows = $data->first();

                if ($rows) {

                    foreach ($parameters as $param) {
                        $name = $param['name'];
                        $type = $param['type'];
                        $value = isset($posts[$name]) ? $posts[$name] : '';
                        $used = $param['used'];
                        $required = $param['required'];

                        if ($required) {
                            if ($type == 'password') {
                                if (!Hash::check($value, $rows->{$name})) {
                                    $result['api_status'] = 0;
                                    $result['api_message'] = 'Invalid credentials. Check your username and password.';

                                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                                        $result['api_authorization'] = $debug_mode_message;
                                    }
                                    goto show;
                                }
                            }
                        } else {
                            if ($used) {
                                if ($value) {
                                    if (!Hash::check($value, $row->{$name})) {
                                        $result['api_status'] = 0;
                                        $result['api_message'] = 'Invalid credentials. Check your username and password.';

                                        if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                                            $result['api_authorization'] = $debug_mode_message;
                                        }
                                        goto show;
                                    }
                                }
                            }
                        }
                    }
                        $this->controller->cbInit();
                        if (!ModuleHelper::can_view($this->controller, $rows)) {
                            //unset($rows[$k]);
                            $rows = [];
                        }

                    foreach ($rows as $k => $v) {

                        $ext = \File::extension($v);
                        if (in_array($ext, $uploads_format_candidate)) {
                            $rows->$k = asset($v);
                        }

                        if (!in_array($k, $responses_fields)) {
                            unset($rows->$k);
                        }
                    }

                    $result['api_status'] = 1;
                    $result['api_message'] = 'success';
                    $result['api_response_fields'] = $responses_fields;
                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                        $result['api_authorization'] = $debug_mode_message;
                    }
                    $rows = (array) $rows;
                    $result = array_merge($result, $rows);
                } else {
                    $result['api_status'] = 0;
                    $result['api_message'] = 'There is no data found !';
                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                        $result['api_authorization'] = $debug_mode_message;
                    }
                }
            } elseif ($action_type == 'delete') {
                $data2 = $data->first();
                $this->controller->cbInit();
                if (ModuleHelper::can_delete($this->controller, $data2)) {

                if (CRUDBooster::isColumnExists($table, 'deleted_at')) {
                    $delete = $data->update(['deleted_at' => date('Y-m-d H:i:s')]);
                } else {
                    $delete = $data->delete();
                }
                $result['api_status'] = ($delete) ? 1 : 0;
                $result['api_message'] = ($delete) ? "success" : "failed";
                if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                    $result['api_authorization'] = $debug_mode_message;
                }
                } else {
                $result['api_status'] = 0;
                $result['api_message'] = "failed";
                if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                    $result['api_authorization'] = $debug_mode_message;
                }
                }

                
            }
        } elseif ($action_type == 'save_add' || $action_type == 'save_edit') {

            $row_assign = [];
            foreach ($input_validator as $k => $v) {
                if (CRUDBooster::isColumnExists($table, $k)) {
                    $row_assign[$k] = $v;
                }
            }
            foreach ($parameters as $param) {
                $name = $param['name'];
                $used = $param['used'];
                $value = isset($posts[$name]) ? $posts[$name] : '';
                if ($used == '1' && $value == '') {
                    unset($row_assign[$name]);
                }
            }

            if ($action_type == 'save_add') {
                if (CRUDBooster::isColumnExists($table, 'created_at')) {
                    $row_assign['created_at'] = date('Y-m-d H:i:s');
                }
            }

            if ($action_type == 'save_edit') {
                if (CRUDBooster::isColumnExists($table, 'updated_at')) {
                    $row_assign['updated_at'] = date('Y-m-d H:i:s');
                }
            }

            $row_assign_keys = array_keys($row_assign);

            foreach ($parameters as $param) {
                $name = $param['name'];
                $value = isset($posts[$name]) ? $posts[$name] : '';
                $config = $param['config'];
                $type = $param['type'];
                $required = $param['required'];
                $used = $param['used'];

                if (!in_array($name, $row_assign_keys)) {

                    continue;
                }

                if ($type == 'file' || $type == 'image') {
                    $row_assign[$name] = CRUDBooster::uploadFile($name, true);
                } elseif ($type == 'base64_file') {
                    $row_assign[$name] = CRUDBooster::uploadBase64($value);
                } elseif ($type == 'password') {
                    $row_assign[$name] = Hash::make(g($name));
                }
            }

            //Make sure if saving/updating data additional param included
            $arrkeys = array_keys($row_assign);
            foreach ($posts as $key => $value) {
                if (!in_array($key, $arrkeys)) {
                    $row_assign[$key] = $value;
                }
            }


            if ($action_type == 'save_add') {
                $this->controller->cbInit();
                if (ModuleHelper::can_add($this->controller, $row_assign)) {
                    if (empty($row->tenant) && Schema::hasColumn($this->controller->table, 'tenant')) {
                        $row_assign['tenant'] = UserHelper::tenant(Session::get('admin_id'));
                    }
                    if (empty($row->group) && Schema::hasColumn($this->controller->table, 'group')) {
                        $row_assign['group'] = UserHelper::primary_group(Session::get('admin_id'));
                    }
                DB::beginTransaction();
                try {
                    $id = DB::table($table)->insertGetId($row_assign);
                    
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw new \Exception($e->getMessage());
                }

                $result['api_status'] = ($id) ? 1 : 0;
                $result['api_message'] = ($id) ? 'success' : 'failed';
                if (CRUDBooster::getSetting('api_debug_mode') == 'true') {

                    $result['api_authorization'] = $debug_mode_message;
                }
                $result['id'] = $id;
                } else {
                    $result['api_status'] =0;
                    $result['api_message'] =  'failed';
                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {

                        $result['api_authorization'] = $debug_mode_message;
                    }

                }
            } else {

                try {
                    $row = DB::table($table)->where('id', $row_assign['id'])->first();
                    $this->controller->cbInit();
                    if (!ModuleHelper::can_edit($this->controller, $row)) {
                        $result['api_status'] = 0;
                        $result['api_message'] = 'Cannot edit this entity!';
                        goto show;
                    }
                    $pk = CRUDBooster::pk($table);
                    $update = DB::table($table);
                    $update->where($table . '.' . $pk, $row_assign['id']);

                    if ($row_api->sql_where) {
                        $update->whereraw($row_api->sql_where);
                    }

                    $this->hook_query($update);

                    $update = $update->update($row_assign);
                    $result['api_status'] = 1;
                    $result['api_message'] = 'success';
                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                        $result['api_authorization'] = $debug_mode_message;
                    }
                } catch (\Exception $e) {
                    $result['api_status'] = 0;
                    $result['api_message'] = 'failed, ' . $e;

                    if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
                        $result['api_authorization'] = $debug_mode_message;
                    }
                }
            }

            // Update The Child Table
            foreach ($parameters as $param) {
                $name = $param['name'];
                $value = isset($posts[$name]) ? $posts[$name] : '';
                $config = $param['config'];
                $type = $param['type'];
                if ($type == 'ref') {
                    if (CRUDBooster::isColumnExists($config, 'id_' . $table)) {
                        DB::table($config)->where($name, $value)->update(['id_' . $table => $lastId]);
                    } elseif (CRUDBooster::isColumnExists($config, $table . '_id')) {
                        DB::table($config)->where($name, $value)->update([$table . '_id' => $lastId]);
                    }
                }
            }
        }

        show:
        $result['api_status'] = $this->hook_api_status ?: $result['api_status'];
        $result['api_message'] = $this->hook_api_message ?: $result['api_message'];

        if (CRUDBooster::getSetting('api_debug_mode') == 'true') {
            $result['api_authorization'] = $debug_mode_message;
        }

        $debug[] = 'Hook before After is OK';
        return response()->json($debug, 200);

        $this->hook_after($posts, $result);

        $debug[] = 'Hook After is OK';
         return response()->json($debug, 200);
        if ($this->output) return response()->json($this->output);

        if ($output == 'JSON') {
            return response()->json($result, 200);
        } else {
            return $result;
        }
    }

    protected function isJSON($theData)
    {
        //return either the array or JSON decoded array
        $test = json_decode($theData[0], true);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid // No error has occurred
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
            Log::info('No JSON');
            $result = (is_object($theData)) ? (array) $theData : $theData;
        } else {
            Log::info('Is JSON');
            $result = $test;
        }

        return $result;
    }

    public function login()
    {
        $x_user = Request::header('X-user');

        $user = DB::table('cms_users')
            ->where('status', '=', 'Active')
            ->where('email', $x_user)

            ->first();
        if (!$user) {
            return "User not found!";
        }

        $priv = DB::table("cms_privileges")
            ->where("id", $user->id_cms_privileges)
            ->first();

        $roles = DB::table('cms_privileges_roles')
            ->where('id_cms_privileges', $user->id_cms_privileges)
            ->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')
            ->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')
            ->where('cms_moduls.deleted_at', null)
            ->get();



        Session::put('admin_id', $user->id);
        Session::put('admin_is_superadmin', $priv->is_superadmin);
        Session::put('admin_name', $user->name);
        Session::put('admin_privileges_roles', $roles);
        Session::put("admin_privileges", $user->id_cms_privileges);
        Session::put('admin_privileges_name', $priv->name);
        Session::put('admin_lock', 0);
        Session::put('theme_color', $priv->theme_color);
        Session::put("appname", CRUDBooster::getSetting('appname'));




        return "OK";
    }
}
