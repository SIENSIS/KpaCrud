<?php

namespace SIENSIS\KpaCrud\Config;

use CodeIgniter\Config\BaseConfig;

/**
 *  
 * @package KpaCrud\Config
 *
 * @version 1.3
 */
class KpaCrud extends BaseConfig
{
    /**
     * configDefaultName - Determines default config collection to use
     *
     * @var string
     * @version 1.3
     */
    public $configDefaultName = "default";

    /**
     * $default - A collection named default, used by KpaCrud if no config is supplied
     * 
     * <pre>
     *  editable        - boolean true*|false    - Defines if row has edit button
     *  removable       - boolean true*|false    - Defines if row has delete button
     * 
     *  lang           - string                - Defines the URL of JS file language for Datatables JQuery tool
     *  sortable       - boolean true*|false    - Defines if table has enabled the sortable feature
     *  filterable     - boolean true*|false    - Defines if table has enabled the searching tool
     *  paging         - boolean true*|false    - Defines if table has enabled the paging tools
     *  numerate       - boolean true*|false    - Defines library numerate rows
     * 
     *  pagingType      - string(full_numbers)  - Determines the paging type, values are: numbers, simple, simple_numbers, full, full_numbers, first_last_numbers
     *  defaultPageSize - int(5)                   - Determines the page size set as default
     *  rememberState   - boolean true|false*    - Defines if table remembers last order column, search, etc
     * 
     *  add_button       - boolean true*|false    - Enables add button in top right toolbar
     *  recycled_button  - boolean true*|false    - Enables trash buttons in top right toolbar (Empty trash, show trash)
     *  exportXLS        - boolean true*|false    - Enables export XLS button in top right toolbar
     *  print            - boolean true*|false    - Enables print button in top right toolbar
     * 
     *  multidelete      - boolean true*|false    - Enables the multi select feature in table list to remove item or to move to trash if softDelete is enabled
     *  deletepermanent  - boolean true*|false    - Enables the multi select feature in table list to remove item permanently if softDelete is enabled
     * 
     *  useSoftDeletes   - boolean true*|false    - Enables the soft delete feature, then items are mark as delete and they can use trash view
     *  showTimestamps   - boolean true|false*    - Enables to show fields created_at and updated_at in view page
     *  createdField     - string (created_at)    - Name of created_at field into database
     *  updatedField     - string (updated_at)    - Name of update_at field into database
     *  deletedField     - string (deleted_at)    - Name of deleted_at field into database
     * 
     * </pre>
     *
     * @var array<string,mixed>
     * @version 1.3
     */

    public $default = [
        'policy' => 'default',
        //row tools
        'editable' => true,
        'removable' => true,

        // table tools
        'langURL' =>  '',
        'sortable' => true,
        'filterable' => true,
        'paging' =>     true,
        'numerate' =>   true,
        /*
        numbers - Page number buttons only
        simple - 'Previous' and 'Next' buttons only
        simple_numbers - 'Previous' and 'Next' buttons, plus page numbers
        full - 'First', 'Previous', 'Next' and 'Last' buttons
        full_numbers - 'First', 'Previous', 'Next' and 'Last' buttons, plus page numbers
        first_last_numbers - 'First' and 'Last' buttons, plus page numbers
        */
        'pagingType' =>   'full_numbers',
        'defaultPageSize' =>   5,
        'rememberState' =>   false,

        // top right toolbar
        'add_button' =>  true,
        'show_button' => true,
        'recycled_button' =>  true,
        'exportXLS' =>   true,
        'print' =>   true,

        // top left list toolbar
        'multidelete' =>  true,
        'deletepermanent' =>   true,

        //data tools & features
        'useSoftDeletes' =>   true,

        'showTimestamps' =>   false,
        'useTimestamps' =>   true,
        'createdField' =>   'created_at',
        'updatedField' =>   'updated_at',
        'deletedField' =>  'deleted_at',

    ];

    /**
     * listView - Collection configured to show only a table list with sortable, paginable, exportable and printable feature enabled
     *
     * @var array<string,mixed>
     *  
     * @see \SIENSIS\KpaCrud\Config\KpaCrud
     * 
     */
    public $listView = [
        'policy' => 'default',
        'editable' => false,
        'removable' => false,

        'sortable' => true,
        'filterable' => false,
        'paging' =>     false,
        'numerate' =>   false,
        'pagingType' =>   'full_numbers',
        'defaultPageSize' =>   10,
        'rememberState' =>   false,

        'add_button' =>  false,
        'show_button' => false,
        'recycled_button' =>  false,
        'exportXLS' =>   true,
        'print' =>   true,

        'multidelete' =>  false,
        'deletepermanent' =>   false,

        'useSoftDeletes' =>   false,

        'showTimestamps' =>   false,
        'useTimestamps' =>   false,
        'createdField' =>   'created_at',
        'updatedField' =>   'updated_at',
        'deletedField' =>  'deleted_at',
    ];

    /**
     * onlyView - Collection configured to show only a table list with sortable, paginable, exportable, filterable and printable feature enabled
     *
     * @var array<string,mixed>
     * 
     * @see \SIENSIS\KpaCrud\Config\KpaCrud
     * 
     */
    public $onlyView = [
        'policy' => 'default',
        'editable' => false,
        'removable' => false,

        'sortable' => true,
        'filterable' => true,
        'paging' =>     true,
        'numerate' =>   false,
        'pagingType' =>   'full_numbers',
        'defaultPageSize' =>   10,
        'rememberState' =>   false,

        'add_button' =>  false,
        'show_button' => false,
        'recycled_button' =>  false,
        'exportXLS' =>   true,
        'print' =>   true,

        'multidelete' =>  false,
        'deletepermanent' =>   false,

        'useSoftDeletes' =>   false,

        'showTimestamps' =>   false,
        'useTimestamps' =>   false,
        'createdField' =>   'created_at',
        'updatedField' =>   'updated_at',
        'deletedField' =>  'deleted_at',
    ];

    /**
     * Constructor
     *
     * @param  string|null $name    Determines default collection, if null default collection is setted as default collection
     * @version 1.3
     */
    public function __construct($name = null)
    {
        parent::__construct();

        if ($name != null)
            $this->configDefaultName = $name;
    }

}
