<?php

/**
 * SIENSIS Dev
 * 
 * @package KpaCrud\Libraries
 * 
 * @version 1.2
 * @author JMFXR <dev@siensis.com> 
 * @copyright 2022 SIENSIS Dev
 * @license MIT
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 * 
 * This code is provided for educational purposes
 */



namespace SIENSIS\KpaCrud\Libraries;

use ReflectionClass;
use SIENSIS\KpaCrud\Models\KpaCrudModel;

/**
 * KpaCrud Library permits to generate automatically CRUD pages from a DB table
 *
 * @link changes.html  To see change log
 * @link todo.html  To see TODO file
 * @link readme.html  To see config samples and features
 * 
 * @version 1.0
 * @version 1.3         Configuration as CI4 config files
 * @version 1.3.0.1     AddWhere function
 * @author JMFXR <dev@siensis.com>
 */
class KpaCrud
{
    /**
     * SQL_TABLE_SEPARATOR - Permit to configure separator in SQL instructions for related fields in related tables, to show it in list/trash views
     */
    public  const SQL_TABLE_SEPARATOR = '__';

    /**
     * DEFAULT_FIELD_TYPE - Field type used to show a textbox
     * 
     * @link readme.html To see samples
     */
    public  const DEFAULT_FIELD_TYPE = 'text';

    /**
     * INVISIBLE_FIELD_TYPE - Field type used to hide a field in all kpacrud pages
     * 
     * @link readme.html To see samples
     */
    public  const INVISIBLE_FIELD_TYPE = 'invisible';

    /**
     * EMAIL_FIELD_TYPE - Field type used to show an input:email and validate field content as a mail
     * 
     * @link readme.html To see samples
     */
    public  const EMAIL_FIELD_TYPE = 'email';

    /**
     * CHECKBOX_FIELD_TYPE - Field type used to show a checkbox
     * 
     * @link readme.html To see samples
     * @see KpaCrud::DEFAULT_CHECK_VALUE       You can set default check/uncheck values with setColumnsInfo
     * @see KpaCrud::DEFAULT_UNCHECK_VALUE
     * 
     * @see KpaCrud::setColumnsInfo()
     * 
     */
    public  const CHECKBOX_FIELD_TYPE = 'checkbox';

    /**
     * NUMBER_FIELD_TYPE - Field type used to show an input:number field and validate its content
     * 
     * @link readme.html To see samples
     */
    public  const NUMBER_FIELD_TYPE = 'number';

    /**
     * RANGE_FIELD_TYPE - Field type used to show a range input, you can set more items with htmlatts property with setColumnsInfo
     * 
     * @link readme.html To see samples
     * @see KpaCrud::setColumnsInfo()
     * 
     */
    public  const RANGE_FIELD_TYPE = 'range';

    /**
     * DATE_FIELD_TYPE - Field type used to show a date picker
     * 
     * @link readme.html To see samples
     */
    public  const DATE_FIELD_TYPE = 'date';

    /**
     * DATETIME_FIELD_TYPE - Field type used to show a datetime picker
     * 
     * @link readme.html To see samples
     */
    public  const DATETIME_FIELD_TYPE = 'datetime';

    /**
     * TEXTAREA_FIELD_TYPE - Field type used to show a textarea 
     * 
     * @link readme.html To see samples
     */
    public  const TEXTAREA_FIELD_TYPE = 'textarea';

    /**
     * DROPDOWN_FIELD_TYPE - Field type used to show a dropdown, combined with options in setColumnsInfo method
     * 
     * @link readme.html To see samples
     * @see KpaCrud::setColumnsInfo()
     * 
     */
    public  const DROPDOWN_FIELD_TYPE = 'dropdown';

    public const DEFAULT_CHECK_VALUE = '1';
    public const DEFAULT_UNCHECK_VALUE = '0';

    /**
     * table - Contains table name for CRUD Generator [REQUIRED].
     * 
     * @var string|null 
     */
    protected $table = null;
    /**
     * request - Current request, to get parameters. 
     *
     * @var object|null
     */
    protected $request = null;
    /**
     * css_files - Contains CSS URLs needed to renderize CRUD Table.
     *             <b>Default</b>: Bootstrap v5 + Bootstrap-Datatables v1.11.5 + Fontawesome v6.1.0
     *             
     * @var array<int,string>
     */
    protected $css_files = array();
    /**
     * js_files - Contains JS URLs needed to renderize CRUD Table.
     *            <b>Default</b>: Bootstrap v5.1.3 + JQuery v3.6 + JQuery Datatables v 1.11.5 + JQuery Bootstrap Datatables v 1.11.5
     *            
     * @var array<int,string>  
     */
    protected $js_files = array();
    /**
     * data_header - Contains the BD column names, used to show in list view or trash view.
     *               If you set Info associated to every column, CRUD shows this info, but if info is not set, CRUD shows upper first case column name
     * 
     * @var array<int,string>
     */
    protected $data_header = array();
    /**
     * data_columns - Contains the display column names to show in list view or trash view. 
     *                If values are not set, CRUD displays upper first case the db column name.
     *                
     * @var array<string,string> 
     */
    protected $data_columns = array();
    /**
     * sortable_columns - Used to configure Datatables sortable feature, you can set every column_name true (sortable) or false (NOT sortable).
     *
     * @var array<string,bool> 
     */
    protected $sortable_columns = array();
    /**
     * data_fields - Contains ALL db columns from CRUD table and related table if you set a relation 1 to N.
     *               Object associated to column name, has info about each column.
     *                  - Field: column name.
     *                  - Type: type(constraint), int(11), varchar(100)...
     *                  - NULL: YES or NO if is a nullable field
     *                  - KEY: PRI if primary key...
     *                  - Default: the default value
     *                  - Extra: information as auto_increment...
     *
     * @var array<string,object>
     */
    protected $data_fields = array();
    /**
     * relations - Contains relation info as a table column name array to a info object, like.
     *                      - relatedTable.
     *                      - relatedField.
     *                      - display_as => if not is set, it uses relatedField as default.
     *                      - options => Contains all values possible to show in edit/add view.
     * 
     * @example 
     *  Relation Workers => Jobs.
     *  <pre>
     *  [
     *      'idjob' => [
     *                   "relatedTable" : "jobs",
     *                   "relatedField" : "id",
     *                   "display_as" : "name",
     *                   "options" : [ 
     *                                  ["id":"PRESI", "name":"President"],
     *                                  ["id":"VPMARK", "name":"VP Marketing"],
     *                                  ["id":"VPSALES", "name":"VP Sales"],
     *                                  ["id":"SALESMAN", "name":"Sales Manager"],
     *                               ]
     *                 ]
     *  ]</pre>
     *
     * @var array<string,object>
     */
    protected $relations = array();
    /**
     * add_css - Boolean to indicate if necessary to load CSS array in all CRUD views.
     *
     * @var boolean
     */
    protected $add_css = true;
    /**
     * add_js - Boolean to indicate if necessary to load JS array in all CRUD views.
     *
     * @var boolean
     */
    protected $add_js = true;
    /**
     * model - BD Model to access database and operate with it.
     *
     * @var KpaCrudModel|null
     *
     * @see \SIENSIS\KpaCrud\Models\KpaCrudModel
     */
    protected $model;
    /**
     * config - Crud configuration.
     *          
     * <pre>
     *   // Key=>value
     * 
     *   // row tools
     *   "editable" => true,
     *   "removable" => true,
     * 
     *   // table tools
     *   "langURL" => 'STRING DIFINED INTO LANG FILE CRUD',
     *   "sortable" => true,
     *   "filterable" => true,
     *   "paging" => true,
     *   "numerate" => true,
     *             
     *   "pagingType" => 'full_numbers',
     *   // numbers - Page number buttons only
     *   // simple - 'Previous' and 'Next' buttons only
     *   // simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
     *   // full - 'First', 'Previous', 'Next' and 'Last' buttons
     *   // full_numbers - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
     *   // first_last_numbers - 'First' and 'Last' buttons, plus page numbers
     * 
     *   "defaultPageSize" => 5,
     *   "rememberState" => false,
     * 
     *   // top right toolbar
     *   "add_button" => true,
     *   "recycled_button" => true,
     *   "exportXLS" => true,
     *   "print" => true,
     * 
     *   // top left list toolbar
     *   "multidelete" => true,
     *   "deletepermanent" => true,
     * 
     *   // data tools & features
     *   "useSoftDeletes" => true,
     *   "showTimestamps" => false,
     *   "useTimestamps" => true,
     *   "createdField" => 'created_at',
     *   "updatedField" => 'updated_at',
     *   "deletedField" => 'deleted_at',
     * </pre>
     *
     * @var array<string,mixed>
     */
    protected $config = array();
    /**
     * output - Contains view generated by CRUD Library
     *
     * @var string|null
     */
    protected $output = null;

    public function __construct($configName = null)
    {
        helper('SIENSIS\KpaCrud\Helpers\crudrender');
        $configFile = config('kpacrud');

        $this->config = $configFile->config($configName);

        $this->request = \Config\Services::request();

        $this->js_files = [
            'https://code.jquery.com/jquery-3.6.0.min.js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',

            'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',

            'https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js',    // Required by BOOTSTRAP Theme
        ];

        $this->css_files = [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css',

            // 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css',   // Classic theme
            'https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css',  // Bootstrap them


            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css'
        ];

        $this->add_css = true;
        $this->add_js = true;

        $this->setConfig($this->config);
    }
    /**
     * output - Returns the generated CRUD page
     *
     * @return string
     * @throws none
     */
    public function output()
    {
        return $this->output;
    }
    /**
     * setConfig - Configures CRUD interfaces and features. You can load configuration values from a KpaCrud config file, 
     * or define a configuration value individually
     * 
     * @example setConfig(['editable'=>false])                      Sets editable configuration to false
     * @example setConfig(['editable'=>false,'removable'=>false])   Sets editable and removable configuration to false
     * @example setConfig('onlyView')                                Loads onlyView configuration parameters defined in KpaCrud config file
     *
     * @param array<string,mixed>|string $config    Config array to define parameters or configName defined in KpaCrud config file
     * @see KpaCrud::$config
     * 
     * @return void
     * @throws none
     */
    public function setConfig($config)
    {
        if (is_string($config)) {
            $configFile = config('kpacrud');
            $config = $configFile->config($config);
        }
        //row tools
        $this->config['editable'] =  $config['editable'] ??  $this->config['editable'] ?? true;
        $this->config['removable'] = $config['removable'] ?? $this->config['removable'] ?? true;

        // table tools
        $this->config['langURL'] =   $config['langURL'] ??   $this->config['langURL'] ?? lang('crud.datatablesLangURL') ?? '';
        $this->config['sortable'] =  $config['sortable'] ??  $this->config['sortable'] ?? true;
        $this->config['filterable'] = $config['filterable'] ?? $this->config['filterable'] ?? true;
        $this->config['paging'] =    $config['paging'] ??    $this->config['paging'] ?? true;
        $this->config['numerate'] =  $config['numerate'] ??  $this->config['numerate'] ?? true;
        /*
        numbers - Page number buttons only
        simple - 'Previous' and 'Next' buttons only
        simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
        full - 'First', 'Previous', 'Next' and 'Last' buttons
        full_numbers - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
        first_last_numbers - 'First' and 'Last' buttons, plus page numbers
        */
        $this->config['pagingType'] =  $config['pagingType'] ??  $this->config['pagingType'] ?? 'full_numbers';
        $this->config['defaultPageSize'] =  $config['defaultPageSize'] ??  $this->config['defaultPageSize'] ?? 5;
        $this->config['rememberState'] =  $config['rememberState'] ??  $this->config['rememberState'] ?? false;

        // top right toolbar
        $this->config['add_button'] = $config['add_button'] ?? $this->config['add_button'] ?? true;
        $this->config['recycled_button'] = $config['recycled_button'] ?? $this->config['recycled_button'] ?? true;
        $this->config['exportXLS'] =  $config['exportXLS'] ??  $this->config['exportXLS'] ?? true;
        $this->config['print'] =  $config['print'] ??  $this->config['print'] ?? true;

        // top left list toolbar
        $this->config['multidelete'] =  $config['multidelete'] ??  $this->config['multidelete'] ?? true;
        $this->config['deletepermanent'] =  $config['deletepermanent'] ??  $this->config['deletepermanent'] ?? true;

        //data tools & features
        $this->config['useSoftDeletes'] =  $config['useSoftDeletes'] ??  $this->config['useSoftDeletes'] ?? true;

        $this->config['showTimestamps'] =  $config['showTimestamps'] ??  $this->config['showTimestamps'] ?? false;
        $this->config['useTimestamps'] =  $config['useTimestamps'] ??  $this->config['useTimestamps'] ?? true;
        $this->config['createdField'] =  $config['createdField'] ??  $this->config['createdField'] ?? 'created_at';
        $this->config['updatedField'] =  $config['updatedField'] ??  $this->config['updatedField'] ?? 'updated_at';
        $this->config['deletedField'] =  $config['deletedField'] ??  $this->config['deletedField'] ?? 'deleted_at';

        if ($this->model != null) {
            $this->model->setConfig($this->config);
        }
    }
    /**
     * setLangURL - To set Datatable Jquery plugin lang file. By default, it uses lang file defined into Crud lang files
     *
     * @param string $langURL
     * @return void
     *
     * @see KpaCrud::$config
     */
    public function setLangURL($langURL)
    {
        $this->langURL = $langURL;
    }

    /**
     * setTable - To set table name to generate CRUD pages
     *
     * @param string $tableName         Table name
     * @param boolean|false $loadPrimaryKeys  [OPTIONAL] set true, to load automatically all primary keys from DB
     * @return void
     * @throws Exception    Table name is null
     * @throws Exception    Table not exists
     */
    public function setTable($tableName, $loadPrimaryKeys = false)
    {
        $db = \Config\Database::connect();

        if ($tableName == null) {
            throw new \Exception(lang('crud.exceptions.tableNull'), 1);
            die;
        } elseif (!$db->tableExists($tableName)) {
            throw new \Exception(lang('crud.exceptions.tableNoExists', [$tableName]), 2);
            die;
        }

        $this->table = $tableName;

        if ($this->model == null) $this->model = new KpaCrudModel();

        $this->model->setTableModelParams($tableName, $this->config, $loadPrimaryKeys);
        $this->data_fields = $this->model->getFields();
    }

    /**
     * setPrimaryKey - Adds primary key name to CRUD library, used if you doesn't use automatic load feature
     *
     * @param string $id
     * @return void
     * @throws Exception    Field no exists
     */
    public function setPrimaryKey($id)
    {
        $db = \Config\Database::connect();
        if (!$db->fieldExists($id, $this->table)) {
            throw new \Exception(lang('crud.exceptions.fieldNoExists', [$id]), 3);
        }
        $this->model->setPrimaryKey($id);
    }
    /**
     * setColumns -> Set columns name to show in CRUD Pages (view/trash)
     *
     * @param array<int,string> $columns    DB Columns name
     * @return void
     * @throws Exception    Table is null
     * @throws Exception    Table no exists
     * @throws Exception    Field no exists
     *
     * @see KpaCrud::$data_header
     */
    public function setColumns($columns)
    {
        $db = \Config\Database::connect();

        if ($this->table == null) {
            throw new \Exception(lang('crud.exceptions.tableNull'), 4);
            die;
        } elseif (!$db->tableExists($this->table)) {
            throw new \Exception(lang('crud.exceptions.tableNoExists', [$this->table]), 5);
            die;
        }

        foreach ($columns as $field) {
            if (!$db->fieldExists($field, $this->table) && strpos($field, "__") == false) {
                throw new \Exception(lang('crud.exceptions.fieldNoExists', [$field]), 6);
            }
        }
        $this->data_header = $columns;
    }

    /**
     * setColumnsInfo - To set display name associated to a db column name
     * 
     * @example setColumnsInfo(['name' => 'Employee name'])     Set Employee name as heder for field name
     * @example setColumnsInfo(['name' => ['name'=>'Employee name', 'type'=>'text'])     Set Employee name as heder for field name and textbox type for edit/add view
     * @link readme.html  To see setColumnsInfo samples
     *
     * @param array<string,mixed> $columns     Associative array, column_name => display_column_name or Associative array, column_name => custom_info
     * @return void
     * @throws exception    Throwns exception if fieldtype is unknown
     *
     * @see KpaCrud::$data_columns
     */
    public function setColumnsInfo($columns)
    {
        $info = array();
        foreach ($columns as $key => $value) {

            if (is_array($value)) //new mode
            {
                $colType = $value["type"] ?? 'text';

                if (!$this->checkFieldType($colType))
                    throw new \Exception(lang('crud.exceptions.fieldTypeUnknown', [$colType]), 7);
                else
                    $info[$key] = $value;
            } else {
                $info[$key]["name"] = $value;
            }
        }
        $this->data_columns = $info;
    }

    /**
     * setSort - To set if a column is sortable in list or trash page
     *
     * @param array<string,boolean> $columns
     * @return void
     * @throws none
     *
     * @see KpaCrud::$sortable_columns
     */
    public function setSort($columns)
    {

        $this->sortable_columns = $columns;
    }

    /**
     * render - Generate crud. Functions uses request info (GET,POST) and query string parameters to operate
     *
     * @return void
     *
     * @see KpaCrud::$ouput
     */
    public function render()
    {
        $db = \Config\Database::connect();

        if ($this->table == null) {
            throw new \Exception(lang('crud.exceptions.tableNull'), 8);
            die;
        } elseif (!$db->tableExists($this->table)) {
            throw new \Exception(lang('crud.exceptions.tableNoExists', [$this->table]), 9);
            die;
        } elseif ($this->model->getPrimaryKey() == null) {
            throw new \Exception(lang('crud.exceptions.idNull'), 10);
            die;
        }

        $this->model->relation1ToN($this->relations);

        if ($this->request->getGet('view') == 'item') {

            return $this->renderView();
        } elseif ($this->request->getGet('edit') && $this->config['editable']) {

            return $this->renderEdit();
        } elseif ($this->request->getGet('del') && $this->config['removable']) {

            return $this->renderDel();
        } elseif ($this->request->getGet('add') && $this->config['add_button']) {

            return $this->renderAdd();
        } elseif ($this->request->getGet('export')) {

            $this->renderExport();
        } elseif ($this->request->getGet('trash') == 'list' && $this->config['recycled_button'] && $this->config['useSoftDeletes']) {

            if ($this->request->getPost('trashop') == 'recover')
                return $this->renderTrashRecover();

            elseif ($this->request->getPost('trashop') == 'empty')
                return $this->renderTrashEmpty();

            elseif ($this->request->getPost('trashop') == 'remove')
                return $this->renderTrashRemove();

            else
                return $this->renderTrash();
        } elseif ($this->request->getGet('list') == 'trash' || $this->request->getGet('list') == 'perm') {
            if ($this->request->getGet('list') == 'trash' && $this->config['multidelete'] && $this->request->getPost('listop') == "removetrash") {
                return $this->renderMoveItemsToTrash();
            } elseif ($this->request->getGet('list') == 'perm' && $this->config['deletepermanent'] && $this->config['useSoftDeletes']  && $this->request->getPost('listop') == "removeperm") {
                return $this->renderTrashRemove();
            } else {
                return $this->renderList();
            }
        } else {

            return $this->renderList();
        }
    }

    /**
     * setRelation - To set a relation 1=>N from CRUD Table with another one
     * 
     * @example setRelation('idjob','jobs','id','name')     Set relation workers.idjob=>jobs.id and show workers.name field
     *
     * @param string $fieldName         Field name from CRUD Table, related
     * @param string $relatedTable      Related table
     * @param string $relatedField      Field name from related table
     * @param string|null $display_as        Field name from related table, to show instead relatedField. If null pages will show first upper case related field name
     * @return void
     * @throws none
     *
     * @see KpaCrud::$relations
     */
    public function setRelation($fieldName, $relatedTable, $relatedField, $display_as = null)
    {
        $this->relations[$fieldName] = array(
            "relatedTable" => $relatedTable,
            "relatedField" => $relatedField,
            "display_as" => $display_as ?? $relatedField,
            "options" => $this->model->getOptions($relatedTable, $relatedField, $display_as)
        );
    }

    /**
     * addWhere - Adds where clause to database query, usefull to show filtered data
     *
     * @param mixed $key            Query expression, name of field, or associative array
     * @param mixed|null $value     If a single key, they compare with this value
     *
     * @version 1.3.0.1
     */
    public function addWhere($key, $value = null)
    {
        $this->model->addWhere($key, $value);
    }




    /**
     * @ignore
     */
    protected function queryIDsToString($queryID)
    {
        $IDsToQuery = "";
        foreach ($queryID as $key => $value) {
            $k = explode(".", $key);

            $IDsToQuery .= "&" . str_rot13($k[1]) . "=" . str_rot13($value);
        }
        return $IDsToQuery;
    }
    /**
     * @ignore
     */
    protected function getQueryID()
    {
        $queryIDs = array();

        foreach ((array)$this->model->getPrimaryKey() as $key) {
            $value = $this->request->getGet(str_rot13($key));
            if ($value != null) {
                $queryIDs[$this->table . "." . $key] = str_rot13($value);
            }
        }
        return $queryIDs;
    }
    /**
     * @ignore
     */
    protected function renderView()
    {
        $data = $this->_pre_render();

        $queryIDs = $this->getQueryID();

        $data['id'] = $this->queryIDsToString($queryIDs);

        $data['data'] = $this->model->getItem($queryIDs);

        if ($data['data'] != null) {
            $data['tableFields'] = $this->data_fields;

            $view = 'SIENSIS\KpaCrud\Views\view';
            return $this->_render($view, $data);
        } else { // item not exists
            $id = implode(', ', $queryIDs);
            $data['alert'] = lang('crud.alerts.notExistsErr', [$id]);
            $data['alert_type'] = 'err';
            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
            return null;
        }
    }
    /**
     * @ignore
     */
    protected function renderAdd()
    {
        $data = $this->_pre_render();

        $data['tableFields'] = $this->data_fields;

        if ($this->request->getPost('op') == 'add') {
            $newID = $this->model->addItem($this->request->getPost(), $this->data_fields);
            $data['newID'] = $newID;
            $view = 'SIENSIS\KpaCrud\Views\add';
            if ($newID < 0) {
                $data['oldForm'] = $this->request->getPost();
            }
        } else {
            $view = 'SIENSIS\KpaCrud\Views\add';
        }

        return $this->_render($view, $data);
    }
    /**
     * @ignore
     */
    protected function renderDel()
    {
        $data = $this->_pre_render();

        $queryIDs = $this->getQueryID();

        $data['id'] = $this->queryIDsToString($queryIDs);

        $data['data'] = $this->model->getItem($queryIDs);

        if ($data['data'] != null) { //item exists. Delete it!
            if ($this->request->getPost('op') == 'del') {
                $affectedRows = $this->model->delItem($queryIDs);

                if ($affectedRows > 0) {
                    $id = implode(', ', $queryIDs);
                    $data['alert'] = lang('crud.alerts.delOk', [$id]);
                    $data['alert_type'] = 'ok';
                } else {
                    $id = implode(', ', $queryIDs);
                    $data['alert'] = lang('crud.alerts.delErr', [$id]);
                    $data['alert_type'] = 'err';
                }
                session()->setFlashdata('alert', $data['alert']);
                session()->setFlashdata('alert_type', $data['alert_type']);

                $response = \Config\Services::response();
                $response
                    ->redirect($this->request->getPath())
                    ->send();
                return null;
            } else {
                $data['tableFields'] = $this->data_fields;
                $view = 'SIENSIS\KpaCrud\Views\delete';
            }
        } else { //item not exists
            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
        }

        return $this->_render($view, $data);
    }
    /**
     * @ignore
     */
    protected function renderEdit()
    {
        $data = $this->_pre_render();

        $queryIDs = $this->getQueryID();

        $data['id'] = $this->queryIDsToString($queryIDs);
        $data['data'] = $this->model->getItem($queryIDs);

        if ($data['data'] != null) {
            if ($this->request->getPost('op') == 'edit') {
                $updated = $this->model->updateItem($this->request->getPost(), $this->data_fields, $this->request->getGet(), $this->data_columns);

                if ($updated) {
                    $id = implode(', ', $queryIDs);
                    $data['alert'] = lang('crud.alerts.updatedOk', [$id]);
                    $data['alert_type'] = 'ok';
                } else {
                    $id = implode(', ', $queryIDs);
                    $data['alert'] = lang('crud.alerts.updatedErr', [$id]);
                    $data['alert_type'] = 'err';
                }
                session()->setFlashdata('alert', $data['alert']);
                session()->setFlashdata('alert_type', $data['alert_type']);

                $response = \Config\Services::response();
                $response
                    ->redirect($this->request->getPath())
                    ->send();
                return null;
            } else {
                $data['tableFields'] = $this->data_fields;
                $view = 'SIENSIS\KpaCrud\Views\edit';
                return $this->_render($view, $data);
            }
        } else { // item not exists
            d($queryIDs);
            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
        }
    }
    /**
     * @ignore
     */
    protected function renderList()
    {
        $data = $this->_pre_render();

        $data['data'] = $this->model->getItems();

        $view = 'SIENSIS\KpaCrud\Views\list';


        return $this->_render($view, $data);
    }
    /**
     * @ignore
     */
    protected function renderTrashEmpty()
    {
        $this->model->purgeDeleted();
        $data['alert'] = lang('crud.alerts.emptyOk');
        $data['alert_type'] = 'ok';

        session()->setFlashdata('alert', $data['alert']);
        session()->setFlashdata('alert_type', $data['alert_type']);

        $response = \Config\Services::response();
        $response
            ->redirect($this->request->getPath())
            ->send();
        return null;
    }
    /**
     * @ignore
     */
    protected function renderMoveItemsToTrash()
    {
        $itemsRemove = $this->request->getPost('remove');

        if ($itemsRemove != null) {

            foreach ($itemsRemove as $item) {
                $ids = json_decode($item);
                $this->model->delItem($ids);
            }

            $data['alert'] = lang('crud.alerts.removedOk');
            $data['alert_type'] = 'ok';

            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
            return null;
        } else {
            $data['alert'] = lang('crud.alerts.removedErr');
            $data['alert_type'] = 'err';

            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
        }
    }
    /**
     * @ignore
     */
    protected function renderTrashRemove()
    {

        $itemsRemove = $this->request->getPost('remove');

        if ($itemsRemove != null) {

            foreach ($itemsRemove as $item) {
                $ids = json_decode($item);
                $this->model->removeItemPermanent($ids);
            }

            $data['alert'] = lang('crud.alerts.removedOk');
            $data['alert_type'] = 'ok';

            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
            return null;
        } else {
            $data['alert'] = lang('crud.alerts.removedErr');
            $data['alert_type'] = 'err';

            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
        }
    }
    /**
     * @ignore
     */
    protected function renderTrashRecover()
    {

        $itemsRecover = $this->request->getPost('recover');

        if ($itemsRecover != null) {

            foreach ($itemsRecover as $item) {
                $ids = json_decode($item);
                $this->model->recoverItem($ids);
            }

            $data['alert'] = lang('crud.alerts.recoverOk');
            $data['alert_type'] = 'ok';

            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
            return null;
        } else {
            $data['alert'] = lang('crud.alerts.recoverErr');
            $data['alert_type'] = 'err';

            session()->setFlashdata('alert', $data['alert']);
            session()->setFlashdata('alert_type', $data['alert_type']);

            $response = \Config\Services::response();
            $response
                ->redirect($this->request->getPath())
                ->send();
        }
    }
    /**
     * @ignore
     */
    protected function renderTrash()
    {
        $data = $this->_pre_render();

        $data['data'] = $this->model->getItems(true); //true is trash

        $view = 'SIENSIS\KpaCrud\Views\trash';

        return $this->_render($view, $data);
    }
    /**
     * @ignore
     */
    protected function renderExport()
    {
        $device = $this->request->getGet('export');
        if ($device == 'xls' && $this->config['exportXLS']) {
            $this->exportToExcel();
        } elseif ($device == 'print' && $this->config['print']) {
            $this->print_webpage();
        }
    }
    /**
     * @ignore
     */
    protected function _pre_render()
    {
        $data['_route'] = $this->request->getPath();
        $data['_table'] = $this->table;
        $data['_data_columns'] = $this->data_columns;
        $data['_data_header'] = $this->data_header;
        $data['_relations'] = $this->relations;
        $data['_sortable_columns'] = $this->sortable_columns;

        $data['primaryKey'] = $this->model->getPrimaryKey();
        $data['config'] = $this->config;

        $data['js_files'] = $this->add_js ? $this->js_files : [];
        $data['css_files'] = $this->add_css ? $this->css_files : [];

        return $data;
    }
    /**
     * @ignore
     */
    protected function _render($view, $data = null)
    {
        helper("form");
        $this->output = view($view, $data);

        return $this->output();
    }
    /**
     * @ignore
     */
    protected function exportToExcel()
    {
        $data = new \stdClass();
        $data->list = $this->model->getItems();

        $this->_export_to_excel($data);
    }
    /**
     * @ignore
     */
    protected function _export_to_excel($data)
    {
        /**
         * No need to use an external library here. The only bad thing without using external library is that Microsoft Excel is complaining
         * that the file is in a different format than specified by the file extension. If you press "Yes" everything will be just fine.
         * */

        @ob_end_clean();

        $string_to_export = "";
        foreach ($this->data_header as $column) {

            $display_as = $this->data_columns[$column]['name'] ?? ucfirst($column);
            $string_to_export .= $display_as . "\t";
        }
        $string_to_export .= "\n";

        foreach ($data->list as $num_row => $row) {
            foreach ($this->data_header as $column) {
                $string_to_export .= $this->_trim_export_string($row[$column]) . "\t";
            }
            $string_to_export .= "\n";
        }

        // Convert to UTF-16LE and Prepend BOM
        $string_to_export = "\xFF\xFE" . mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

        $filename = "export-" . $this->table . "-" . date("Y-m-d_H:i:s") . ".xls";

        header('Content-type: application/vnd.ms-excel;charset=UTF-16LE');
        header('Content-Disposition: attachment; filename=' . $filename);
        header("Cache-Control: no-cache");
        echo $string_to_export;
        die();
    }
    /**
     * @ignore
     */
    protected function _trim_export_string($value)
    {
        $value = str_replace(array("&nbsp;", "&amp;", "&gt;", "&lt;"), array(" ", "&", ">", "<"), $value);
        return  strip_tags(str_replace(array("\t", "\n", "\r"), "", $value));
    }
    /**
     * @ignore
     */
    protected function print_webpage()
    {
        $data = new \stdClass();
        $data->list = $this->model->getItems();

        @ob_end_clean();
        $this->_print_webpage($data);
    }
    /**
     * @ignore
     */
    protected function _print_webpage($data)
    {
        $string_to_print = "<html><meta charset=\"utf-8\" /><style type=\"text/css\" >
		#print-table{ color: #000; background: #fff; font-family: Verdana,Tahoma,Helvetica,sans-serif; font-size: 13px;}
		#print-table table tr td, #print-table table tr th{ border: 1px solid black; border-bottom: none; border-right: none; padding: 4px 8px 4px 4px}
		#print-table table{ border-bottom: 1px solid black; border-right: 1px solid black}
		#print-table table tr th{text-align: left;background: #ddd}
		#print-table table tr:nth-child(odd){background: #eee}</style>
        <title>" . ucFirst($this->table) . " print version</title>
        <body>";
        $string_to_print .= "<div id='print-table'>";

        $string_to_print .= '<table width="100%" cellpadding="0" cellspacing="0" ><tr>';
        foreach ($this->data_header as $column) {
            $display_as = $this->data_columns[$column]['name'] ?? ucfirst($column);
            $string_to_print .= "<th>" . $display_as . "</th>";
        }
        $string_to_print .= "</tr>";

        foreach ($data->list as $num_row => $row) {
            $string_to_print .= "<tr>";
            foreach ($this->data_header as $column) {
                $string_to_print .= "<td>" . $this->_trim_print_string($row[$column]) . "</td>";
            }
            $string_to_print .= "</tr>";
        }

        $string_to_print .= "</table></div></body></html>";

        echo $string_to_print;
        die();
    }
    /**
     * @ignore
     */
    protected function _trim_print_string($value)
    {
        $value = str_replace(array("&nbsp;", "&amp;", "&gt;", "&lt;"), array(" ", "&", ">", "<"), $value);

        //If the value has only spaces and nothing more then add the whitespace html character
        if (str_replace(" ", "", $value) == "")
            $value = "&nbsp;";

        return strip_tags($value);
    }
    /**
     * @ignore 
     */
    public function checkFieldType($strType)
    {
        $oClass = new \ReflectionClass(__CLASS__);

        $constants = $oClass->getConstants();
        foreach ($constants as $key => $value) {
            if ($value == $strType && str_ends_with($key, "_FIELD_TYPE"))
                return true;
        }
        return false;
    }
}
