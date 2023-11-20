<?php

/**
 * SIENSIS Dev
 * 
 * @package KpaCrud\Models
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

namespace SIENSIS\KpaCrud\Models;


use CodeIgniter\Model;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

/**
 * KpaCrud Model permits to access every table to generate CRUD pages
 *
 * @version 1.0
 * @version 1.3.0.1 AddWhere
 * @author JMFXR <dev@siensis.com>
 */
class KpaCrudModel extends Model
{

    /**
     * primaryKey - Stores all primary key associated to table, it can be set manually or automatically.
     *
     * @var array<int,string>
     */
    protected $primaryKey = array();
    /**
     * table - Defines current model table, used to generate pages and get info.
     *
     * @var string
     */
    protected $table = '';
    /**
     * builder - Query builder provided by codeigniter, to generate query speedly.
     *
     * @var BaseBuilder|null
     * @link https://codeigniter.com/user_guide/database/query_builder.html
     */
    protected $builder;
    /**
     * protectFields - Used to indicate if fields are protected, this functions is used by CI4 but KpaCrud manage itself this restriction.
     *
     * @var boolean 
     * @see \CodeIgniter\Model
     */
    protected $protectFields    = false;
    /**
     * useSoftDeletes - Indicates if table has enabled CI4 softDeletes feature, this feature is enabled by Library.
     *
     * @var boolean
     * @see \CodeIgniter\Model
     */
    protected $useSoftDeletes   = true;
    /**
     * useTimestamps - Indicates if table contains created_at, updated_at fields to manage during insert and update routines.
     *
     * @var boolean
     * @see \CodeIgniter\Model
     */
    protected $useTimestamps = true;
    /**
     * dateformat - Indicates type of column that create_at and updated_at are expected, allowed: 'datetime', 'date', 'int'.
     *
     * @var string
     * @see \CodeIgniter\Model
     */
    protected $dateFormat    = 'datetime';
    /**
     * createdField - Column name used for insert timestamps.
     *
     * @var string
     * @see \CodeIgniter\Model
     */
    protected $createdField  = 'created_at';
    /**
     * updatedField - Column name used for update timestamps.
     *
     * @var string
     * @see \CodeIgniter\Model
     */
    protected $updatedField  = 'updated_at';
    /**
     * deletedField - Column name used to save soft delete state.
     *
     * @var string
     * @see \CodeIgniter\Model
     */
    protected $deletedField  = 'deleted_at';


    public function __construct()
    {
        parent::__construct();
    }
    /**
     * setConfig - Configures model with $config parameter or default values if config are miss.
     *
     * @param  array<string,mixed> $config
     * 
     * @see KpaCrudModel::$useTimestamps
     * @see KpaCrudModel::$dateFormat
     * @see KpaCrudModel::$createdField
     * @see KpaCrudModel::$updatedField
     * @see KpaCrudModel::$deletedField
     */
    public function setConfig($config)
    {

        $this->useSoftDeletes =   $config['useSoftDeletes'] ?? true;

        $this->useTimestamps =   $config['useTimestamps'] ?? true;
        $this->dateFormat =   $config['dateFormat'] ?? 'datetime';
        $this->createdField =   $config['createdField'] ?? 'created_at';
        $this->updatedField =   $config['updatedField'] ?? 'updated_at';
        $this->deletedField =   $config['deletedField'] ?? 'deleted_at';
    }
    /**
     * setTableModelParams - This function initialize table name, query builder and other config parameters.
     *
     * @param  string  $table_name              Table name used by CRUD model.
     * @param  string  $config                  CI4 Model parameters.
     * @param  boolean $loadPrimaryKeys   If set true model loads automatically all primary key.
     * 
     * @see KpaCrudModel::setConfig
     * @see KpaCrudModel::$table
     * @see KpaCrudModel::$builder
     */
    public function setTableModelParams($table_name, $config, $loadPrimaryKeys = false)
    {
        $this->table = $table_name;
        $this->setConfig($config);
        $this->builder = $this->db->table($table_name);
        $this->builder->resetQuery();

        $this->builder->select("$table_name.*");

        if ($loadPrimaryKeys) {
            $this->loadPrimaryKeys();
        }
    }

    /**
     * setPrimaryKey - Adds a primary key to primary keys array, to permit model generate needed query.
     *
     * @param  string $field_name
     */
    public function setPrimaryKey($field_name)
    {
        $this->primaryKey[] = $field_name;
    }
    /**
     * getPrimaryKey - Returns all defined primary keys.
     *
     * @return array<int,string>    String array with all defined primary keys.
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    /**
     * getFields - Function that returns an array column name from current table or table specified.
     *              Object associated to column name, has info about each column.
     *                  - Field: column name.
     *                  - Type: type(constraint), int(11), varchar(100)...
     *                  - NULL: YES or NO if is a nullable field
     *                  - KEY: PRI if primary key...
     *                  - Default: the default value
     *                  - Extra: information as auto_increment...
     *
     * @param  string $tablename Table name to return column names, if null current table name is used.
     * 
     * @return array<string,object>
     * 
     * @see KpaCrud::$data_fields
     */
    public function getFields($tablename = null)
    {
        /*
        $fields = $this->db->getFieldData($this->table);
        foreach ($fields as $field) {
            public default ->
            public max_length -> integer 
            public name -> string 
            public nullable -> boolean 
            public primary_key -> integer 
            public type -> string 
        }
        */

        if ($tablename == null)
            return $this->db->query("SHOW COLUMNS FROM `{$this->table}`")->getResultObject();
        else
            return $this->db->query("SHOW COLUMNS FROM `{$tablename}`")->getResultObject();
    }
    /**
     * addItem - Add item to table, inserts data from a form with fields required by table.
     *
     * @param  array<string,mixed> $post        Request POST array with all data send by page.
     * @param  array<int,string> $data_fields       Required and configured data table fields.
     * 
     * @return int|string                       Insert ID.
     * 
     * @see \Codeigniter\Database\BaseConnection
     */
    public function addItem($post, $data_fields)
    {
        try {
            $insert_array = array();
            // search if item exists
            $where_array = array();
            foreach ((array)$this->primaryKey as $key) {
                $where_array[$key] = $post["data_" . $key];
            }
            $query = $this->builder->where($where_array)->get()->getRowArray();

            if ($query != null) {
                return -1;
            }
            foreach ((array)$data_fields as $field) {
                if (isset($post["data_" . $field->Field])) $insert_array[$field->Field] = $post["data_" . $field->Field];
            }
            $insert = $this->ignore(true)->insert($insert_array);

            return $this->db->insertID();
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * updateItem - Function to update item into BBDD, uses data from POST request and Keys from GET request.
     *
     * @param  array<string,mixed> $post        Request POST array with all data send by page.
     * @param  array<int,string> $data_fields       Required and configured data table fields, used in CRUD.
     * @param  array<string,string> $get        Required GET array, that contains table primary keys.
     * 
     * @return boolean                          Returns true or false, if updated is ok.
     */
    public function updateItem($post, $data_fields, $get, $column_info)
    {
        $update_array = array();
        $where_array = array();
        foreach ((array)$this->primaryKey as $key) {
            $where_array[$key] = str_rot13($get[str_rot13($key)]);
        }
        // d($post);
        foreach ((array)$data_fields as $field) {

            $coltype = $column_info[$field->Field]['type'] ?? KpaCrud::DEFAULT_FIELD_TYPE;
            if ($coltype != KpaCrud::INVISIBLE_FIELD_TYPE) {
                if (isset($post["data_" . $field->Field])) {
                    $update_array[$field->Field] = $post["data_" . $field->Field];
                } elseif ($coltype == 'checkbox') {
                    $val = $column_info[$field->Field]['uncheck_value'] ?? $field->Default ?? KpaCrud::DEFAULT_UNCHECK_VALUE;
                    $update_array[$field->Field] = $val;
                }
            }
        }

        $updated = $this->where($where_array)->set($update_array)->update();
        
        // dd($update_array,$updated);
        return $updated;
    }
    /**
     * getItem - Get item from table, using primary keys array set in model.
     *
     * @param  array<string,mixed> $ids_array   Contains associative array of primary_key_name => value.
     * 
     * @return array<int,mixed>                            Returns data array get from DB Query.
     */
    public function getItem($ids_array)
    {
        $query = $this->builder->where($ids_array)->get()->getRowArray();
        return $query;
    }
    /**
     * getItems - Get array items from table, if softDelted flag is set, they can return only deleted elements.
     *
     * @param  boolean $trashItems    True indicates that trash items are needed, not deleted items otherwise.
     * 
     * @return array<int,mixed>                        Returns data array get from DB Query.
     */
    public function getItems($trashItems = false)
    {
        if ($this->useSoftDeletes) {
            if ($trashItems) {
                if ($this->db->fieldExists($this->deletedField, $this->table))
                    $this->builder->where($this->table . "." . $this->deletedField . ' IS NOT NULL');
            } else {
                if ($this->db->fieldExists($this->deletedField, $this->table))
                    $this->builder->where($this->table . "." . $this->deletedField . ' IS NULL');
            }
        }
        $pkey = (array) $this->primaryKey;

        foreach ($pkey as $key) {
            $query = $this->builder->orderBy($key, 'DESC');
        }

        $query = $this->builder->get()->getResultArray();

        return $query;
    }
    /**
     * removeItemPermanent - Removes permanently a single item from a table.
     *
     * @param  array<string,mixed> $arrayid     Contains associative array of primary_key_name => value.
     * 
     */
    public function removeItemPermanent($arrayid)
    {
        foreach ($arrayid as $key => $value) {
            $this->builder->where($key, $value);
        }

        $this->delete([], true);
    }
    /**
     * recoverItem - Recovers a single item from table, if softDeletes flag is set.
     *
     * @param  array<string,mixed> $arrayid     Contains associative array of primary_key_name => value.
     */
    public function recoverItem($arrayid)
    {
        foreach ($arrayid as $key => $value) {
            $this->builder->where($key, $value);
        }
        $this->builder->set($this->deletedField, null)->update();
    }
    /**
     * delItem - Deletes a single item from table where $ids_array match.
     *
     * @param  array<string,mixed> $ids_array   Contains associative array of primary_key_name => value.
     * 
     * @return int                              Returns number of affected rows by delete operation.
     */
    public function delItem($ids_array)
    {
        if ($ids_array != null) {
            foreach ($ids_array as $key => $value) {
                $this->where($key, $value);
            }
            $query = $this->delete();
            return $this->affectedRows();
        } else
            return false;
    }
    /**
     * relation1ToN - Sets all relation 1=>N from model table with other ones.
     *
     * @param  array<int,mixed> $relations      Array object, that contains all defined relations
     * 
     * @return KpaCrudModel
     * 
     * @see KpaCrud::setRelation
     * @see KpaCrud::$relations
     * @see KpaCrud::getOptions
     */
    public function relation1ToN($relations)
    {
        $this->addSelects($relations);

        foreach ($relations as $fieldName => $data) {

            if ($this->table==$data['relatedTable']){
                $tablename = "rel".$data['relatedTable'];
                $condition = "`$this->table`.`$fieldName`=`$tablename`.`{$data['relatedField']}`";
                $this->builder->where($condition);
            }else {
                $relatedTableName="rel" . $data['relatedTable'];
                $condition = "`$this->table`.`$fieldName`=`$relatedTableName`.`{$data['relatedField']}`";
                $this->builder->join($data['relatedTable'] . " as " . $relatedTableName, $condition);
            }
        }

        return $this;
    }
    /**
     * getOptions - Gets all values from relatedTable, to show in edit/add pages
     *
     * @param  string $relatedTable     Related table name to get values
     * @param  string $relatedField     Related field name used to identify values
     * @param  string $display_as       Field name from related table, used to show
     * 
     * @return array<string,mixed>     Returns Array key=>value where value is display field name
     * 
     * @see KpaCrud::$relations
     */
    public function getOptions($relatedTable, $relatedField, $display_as)
    {
        if ($display_as == null) $value = $relatedField;
        else $value = $display_as;

        $query = $this->db->table($relatedTable)->orderBy($display_as, 'ASC')->get();
        $data = array();

        foreach ($query->getResult() as $row) {
            $id = $row->$relatedField;
            $data[$id] = $row->$value;
        }
        return $data;
    }


    /**
     * addWhere - Adds where clause to query builder, usefull to show filtered data
     * 
     * @param mixed $key            Query expression, name of field, or associative array
     * @param mixed|null $value     If a single key, they compare with this value
     *
     * @version 1.3.0.1
     */
    public function addWhere($key, $value = null)
    {
        $this->builder->where($key, $value, true);
    }

    
    /**
     * orWhere - Adds where clause to query builder concatenated with OR conjuntion, usefull to show filtered data
     * 
     * @param mixed $key            Query expression, name of field, or associative array
     * @param mixed|null $value     If a single key, they compare with this value
     *
     * @version 1.4.5
     */
    public function orWhere($key, $value = null)
    {
        $this->builder->orWhere($key, $value);
    }

    /**
     * @ignore
     */
    protected function loadPrimaryKeys()
    {
        $fields = $this->db->getFieldData($this->table);
        foreach ($fields as $field) {
            if ($field->primary_key > 0) $this->setPrimaryKey($field->name);
        }
    }
    /**
     * @ignore
     */
    protected function addSelects($relations)
    {
        foreach ($relations as $fieldName => $data) {
            $fieldsRelatedTable = $this->getFields($data['relatedTable']);

            foreach ($fieldsRelatedTable as $field) {
                $as = $data['relatedTable'] . KpaCrud::SQL_TABLE_SEPARATOR . $field->Field;
                $selTablename="rel" . $data['relatedTable'];

                $this->builder->select("$selTablename.{$field->Field} as $as");
            }

            //$this->builder->from ($data['relatedTable'] . " as rel" . $data['relatedTable']);
        }
    }
} 
/* End of file CrudGenModel.php (20220318) */
