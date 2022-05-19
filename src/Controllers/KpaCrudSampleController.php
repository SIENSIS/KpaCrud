<?php

namespace SIENSIS\KpaCrud\Controllers;

use \App\Controllers\BaseController;

use SIENSIS\KpaCrud\Libraries\KpaCrud;

/**
 * KpaCrudSampleController - Is a sample controller, to view how to use KpaCrud Library.
 *                           - Shows a simple table like News
 *                           - Shows a table with two primary keys
 *                           - Shows a table whit two 1=>N relations
 *                           - Shows a table with a 1=>N relation workers=>jobs
 * 
 * @link ../readme.md     To view KpaCrud functions and samples, to customize your expirience
 *  
 * @package App\Controllers
 */
class KpaCrudSampleController extends BaseController
{

    public function hashNewPassword($postData)
    {
        $postData['data_password_hash'] = password_hash($postData['data_password_hash'], PASSWORD_DEFAULT);
        return $postData; // if return null, edit process will be cancelled
    }
    public function hashEditPassword($postData)
    {
        if ($postData['data_password_hash'] != $postData['olddata_password_hash']) {
            // field has a new value. You new to generate new password
            $postData['data_password_hash'] = password_hash($postData['data_password_hash'], PASSWORD_DEFAULT);
        } // else field not changed, you can update with the same value
        return $postData;  // if return null, edit process will be cancelled
    }
    public function myCustomPage($obj)
    {
        $this->request->getUri()->stripQuery('customf');
        $this->request->getUri()->addQuery('customf', 'mpost');

        $html = "<div class=\"container-lg p-4\">";
        $html .= "<form method='post' action='" . base_url($this->request->getPath()) . "?" . $this->request->getUri()->getQuery() . "'>";
        $html .= csrf_field()  . "<input type='hidden' name='test' value='ToSend'>";
        $html .= "<div class=\"bg-secondary p-2 text-white\">";
        $html .= "	<h1>View item</h1>";
        $html .= "</div>";
        $html .= "	<div style=\"margin-top:20px\" class=\"border bg-light\">";
        $html .= "		<div class=\"d-grid\" style=\"margin-top:20px\">";
        $html .= "			<div class=\"p-2 \">	";
        $html .= "				<label>Username</label>	";
        $html .= "				<div class=\"form-control bg-light \">";
        $html .= $obj['username'];
        $html .= "				</div>";
        $html .= "			</div>";
        $html .= "";
        $html .= "			<div class=\"p-2 \">	";
        $html .= "				<label>eCorreu</label>	";
        $html .= "				<div class=\"form-control bg-light\">";
        $html .= $obj['email'];
        $html .= "				</div>";
        $html .= "			</div>";
        $html .= "			";
        $html .= "		</div>";
        $html .= "	</div>";
        $html .= "<div class='pt-2'><input type='submit' value='Envia'></div></form>";
        $html .= "</div>";

        // You can load view info from view file and return to KpaCrud library
        // $html = view('view_route/view_name');

        return $html;
    }
    public function myCustomPagePost($obj)
    {
        // $obj contains info about register if you repeat querystring received in MyCustomPage
        $html = '<h1>Operation ok</h1>';

        /*
        Do something with this->request->getPost information
        */
        return $html;
    }
    public function demo_simpleTable_full()
    {
        $crud = new KpaCrud(); //loads default configuration

        $crud->setTable('users');
        $crud->setPrimaryKey('id');

        if ($crud->isExportMode()) {
            $crud->setColumns(['username', 'email', 'active']);
            $crud->addWhere('users.active=1');
        } else {

            $crud->setColumns(['id', 'email', 'username']);
        }

        $crud->addPostAddCallBack(array($this, 'hashNewPassword'));
        $crud->addPostEditCallBack(array($this, 'hashEditPassword'));

        // Create an button icon in every register
        $crud->addItemFunction('mailing', 'fa-paper-plane', array($this, 'myCustomPage'), "Enviar un mail");
        // Create an invisible named function in KpaCrud to call after
        $crud->addItemFunction('mpost', '', array($this, 'myCustomPagePost'), "", false);

        /**
         * Available options:
         *
         * - name -> Field name to show user in pages
         * - type -> Field type, available types are:
         *         DEFAULT_FIELD_TYPE 
         *         INVISIBLE_FIELD_TYPE
         *         EMAIL_FIELD_TYPE
         *         CHECKBOX_FIELD_TYPE
         *         NUMBER_FIELD_TYPE 
         *         RANGE_FIELD_TYPE 
         *         DATE_FIELD_TYPE 
         *         DATETIME_FIELD_TYPE 
         *         TEXTAREA_FIELD_TYPE 
         * - default -> default value into add page
         * - check_value/uncheck_value -> You can configure check&uncheck values for a checkbox to store correctly into bd, by default are check=1 / uncheck=0
         * - html_atts -> Permits to add html attribs to a input field, like: required, placeholder, pattern, title, min, max, step... 
         * - options -> Options to show in a dropdown field
         */
        $crud->setColumnsInfo([
            'id' => ['name' => 'Codi'],
            'email' => [
                'name' => 'eCorreu',
                'html_atts' => [
                    'required',
                    'placeholder="Introdueix l\'adreça mail de l\'usuari"'
                ],
                'type' => KpaCrud::EMAIL_FIELD_TYPE
            ],
            'username' => [
                'name' => 'Nom usuari',
                'html_atts' => [
                    "required",
                    "placeholder=\"Introdueix el nom d'usuari\""
                ],
            ],
            // 'active' => [
            //     'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
            //     'options' => ['' => "Tria opcio", 'Usuari desactivat', 'Usuari actiu'],
            //     'html_atts' => [
            //         "required",
            //     ],
            //     'default'=>'1'
            // ],
            'active' => [
                'type' => KpaCrud::CHECKBOX_FIELD_TYPE,
                'html_atts' => [
                    "required",
                ],
                'default' => '1',
                'check_value' => '1',
                'uncheck_value' => '0'
            ],
            'password_hash' => [
                'type' => KpaCrud::PASSWORD_FIELD_TYPE,
                'name' => 'Password',
                'html_atts' => []
            ],

            // 'force_pass_reset' => [
            //     'name' => 'Forçar reset password',
            //     'type' => KpaCrud::CHECKBOX_FIELD_TYPE,
            //     'default'=>'1',
            //     'check_value' => '1',
            //     'uncheck_value' => '0'
            // ],
            'force_pass_reset' => [
                'name' => 'Forçar reset password',
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => ['' => "Tria opcio", 'Sense canvi password', 'Canviar password'],
                'html_atts' => [
                    "required",
                ],
                'default' => '0',
            ],

            'reset_expires' => [
                'type' => KpaCrud::DATE_FIELD_TYPE,
                'default' => date('Y-m-d', strtotime(date("d-m-Y") . ' + 6 days'))

            ],
            'activate_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
            'reset_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
            'reset_at' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
            'status' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
            'status_message' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],

        ]);

        $data['output'] = $crud->render();

        return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
    }



    /**
     * demo_simpleTable_onlyview - Demo to shows how to get a table CRUD pages, where table has not relations and only a primary key
     * 
     * <pre>
     *  $crud = new KpaCrud();                          // loads default configuration    
     *  $crud->setConfig('onlyView');                   // sets configuration to onlyView
     *                                                  // set into config file
     *  $crud->setTable('news');                        // set table name
     *  $crud->setPrimaryKey('id');                     // set primary key
     *  $crud->setColumns(['id', 'title', 'data_pub']); // set columns/fields to show
     *  $crud->setColumnsInfo([                         // set columns/fields name
     *        'id' => 'Codi',
     *        'title' => 'Titol',
     *        'data_pub' => 'Data publicació',
     *  ]);
     *  $crud->addWhere('news.data_pub>="2022-04-02 21:03:48"'); // show filtered data
     *  $data['output'] = $crud->render();              // renders view
     *  return view('sample', $data);
     * </pre>
     * 
     * @link ../readme.md     To show more functions and samples, to customize your expirience
     *
     * @return void
     * 
     * @version 1.3
     */
    public function demo_simpleTable_onlyview()
    {
        $crud = new KpaCrud('listView'); //loads listView configuration

        /**
         * KpaCrud constructor - Loads library with a configuration difined into KpaCrud file
         * 
         * @example $crud = new KpaCrud('listView');  Loads KpaCrud with listView defined in Config\KpaCrud.php file
         * 
         */

        /**
         * setConfig - Sets all config values with configuration defined into KpaCrud file
         * 
         * @example $crud->setConfig('onlyView');      Set all KpaCrud configuration to values defined as onlyView in Config\KpaCrud.php file 
         */

        // $crud->setTable('news', true);    // Primary key autoload feature
        // or manual primary key set
        $crud->setTable('news');
        $crud->setPrimaryKey('id');

        /**
         * setColumns([column_name1,column_name2,column_name2])
         * 
         * To set table columns to show into CRUD datatable. The column name is
         * the column name with first upper case
         * 
         * If column_name not exists it throws an exception
         * 
         */
        $crud->setColumns(['id', 'title', 'data_pub']);

        /**
         * addWhere ($expression)  or addWhere ($key, $value)
         * 
         * @example 
         *      addWhere('news.id','20'); 
         *      - Adds as a filter news ID equal to 20
         * @example 
         *      addWhere('news.data_pub>="2022-04-02 21:03:48"');    
         *      - Adds as a filter data_pub for news are after 2022-04-02 21:03:48
         * 
         * This function adds a filter when KpaCrud gets items
         */
        $crud->addWhere('news.data_pub>="2022-04-02 21:03:48"');
        // $crud->addWhere('news.id','20');

        /**
         * setColumnsInfo ([column_name1 => title1, column_name2 => title2, column_name3 => title3])
         * 
         * Column name by default is shown with first upper case, if you like to change its name
         * you can use setColumnsInfo, to associate a title to a column_name. You can easily
         * set with an array with all your titles
         * 
         */
        $crud->setColumnsInfo([
            'id' => ['name' => 'Codi', 'type' => 'text'],
            'title' => 'Titol',
            'data_pub' => ['name' => 'Data publicació'],
        ]);
        /**
         * setConfig(config_array) or setConfig(config_collection_name)
         * 
         * This function permits to set parametres to configure your CRUD, you
         * can call function to set parameters individually if you need
         * 
         */

        $crud->setConfig('onlyView');
        $crud->setConfig(["editable" => false,]);

        /**
         * render()
         * 
         * Returns html+css+js generated for CRUD, to insert into your project
         */

        $data['output'] = $crud->render();

        return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
    }

    /**
     * demo_multikey - Demo to shows how to get a table CRUD pages, where table has two primary keys and no relations
     * 
     * <pre>
     *  $crud = new KpaCrud();                          // loads default configuration    
     *  $crud->setConfig('onlyView');                   // sets configuration to onlyView
     *                                                  // set into config file
     *  $crud->setTable('tokens');                        // set table name
     *  $crud->setPrimaryKey('tokenid');                     // set primary key
     *  $crud->setPrimaryKey('subject');                     // set primary key
     *  $crud->setColumns(['tokenid', 'subject', 'expiration']); // set columns/fields to show
     *  $crud->setColumnsInfo([                         // set columns/fields name
     *        'tokenid' => 'Token',
     *  ]);
     * $crud->setSort([
     *             'tokenid' => false,
     *             'subject' => false,
     *             'expiration' => false
     *         ]);
     *  $data['output'] = $crud->render();              // renders view
     *  return view('sample', $data);
     * </pre>
     *
     * @return void
     * 
     * @version 1.3
     */

    public function demo_multikey()
    {
        $crud = new KpaCrud();

        $crud->setTable('tokens');
        $crud->setPrimaryKey('tokenid');
        $crud->setPrimaryKey('subject');

        $crud->setColumns(['tokenid', 'subject', 'expiration']);

        $crud->setColumnsInfo([
            'tokenid' => 'Token',
        ]);

        /**
         * setSort (column_sort_config)
         * 
         * All columns are sortable if sortable config parameter is set to true, to change
         * configuration for a column, you can disable particularly with this function, the
         * parameter column_sort_config is a associative array with column name with a boolean
         * to enable/disable order feature 
         */
        $crud->setSort([
            'tokenid' => false,
            'subject' => false,
            'expiration' => false
        ]);

        /**
         * setConfig(config_array) or setConfig(config_collection_name)
         * 
         * This function permits to set parametres to configure your CRUD, you
         * can call function to set parameters individually if you need
         * 
         */

        $crud->setConfig('onlyView');

        $data['output'] = $crud->render();
        $data['title'] = 'Demo multikey';
        return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
    }


    /**
     * demo_relationNM - Demo to shows how to get a table CRUD pages, where table is in the middle of two table, usually a NM relation
     * 
     * <pre>
     *  $crud = new KpaCrud('listView');                          // loads listView configuration    
     * 
     *  $crud->setTable('auth_groups_users');                        // set table name
     * 
     *  $crud->setPrimaryKey('group_id');                     // set primary key
     *  $crud->setPrimaryKey('user_id');                     // set primary key
     * 
     * // display_as is the column name to show in edit / view mode
     * // if not set, relatedfield is shown
     *  $crud->setRelation('group_id', 'auth_groups', 'id', 'name');
     *  $crud->setRelation('user_id', 'users', 'id', 'username');
     * 
     *  $crud->setColumns(['auth_groups__name', 'users__username', 'users__email']);
     *  $crud->setColumnsInfo([
     *       'auth_groups__name' => 'Rol',
     *       'users__username' => 'Usuari',
     *       'users__email' => 'eMail',
     *  ]);
     * $crud->setSort([
     *             'tokenid' => false,
     *             'subject' => false,
     *             'expiration' => false
     *         ]);
     *  $data['output'] = $crud->render();              // renders view
     *  return view('sample', $data);
     * </pre>
     *
     * @return void
     * 
     * @version 1.3
     */

    public function demo_relationNM()
    {
        $crud = new KpaCrud('listView');

        $crud->setTable('auth_groups_users');
        $crud->setPrimaryKey('group_id');
        $crud->setPrimaryKey('user_id');

        // display_as is the column name to show in edit / view mode
        // if not set, relatedfield is shown
        $crud->setRelation('group_id', 'auth_groups', 'id', 'name');
        $crud->setRelation('user_id', 'users', 'id', 'username');

        $crud->setColumns(['auth_groups__name', 'users__username', 'users__email']);

        $crud->setColumnsInfo([
            'auth_groups__name' => 'Rol',
            'users__username' => 'Usuari',
            'users__email' => 'eMail',
        ]);

        /**
         * setConfig(config_array) or setConfig(config_collection_name)
         * 
         * This function permits to set parametres to configure your CRUD, you
         * can call function to set parameters individually if you need
         * 
         */

        $data['output'] = $crud->render();
        $data['title'] = 'Demo relation NM';
        return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
    }


    /**
     * demo_relation1N - Demo to shows how to get a table CRUD pages, where table has a unique relation
     * 
     * <pre>
     *  $crud = new KpaCrud();                          // loads default configuration    
     * 
     *  $crud->setTable('workers');                        // set table name
     * 
     *  $crud->setPrimaryKey('id');                     // set primary key
     * 
     * // display_as is the column name to show in edit / view mode
     * // if not set, relatedfield is shown
     *  $crud->setRelation('idjob', 'jobs', 'id', 'name');
     * 
     *  $crud->setColumns(['id', 'name', 'surname', 'jobs__name']);
     * 
     *  $crud->setConfig('onlyView');
     *  $crud->setConfig(["editable" => false,]);
     * 
     *  $crud->setColumnsInfo([
     *     'name' => 'Nom',
     *     'surname' => 'Cognom',
     *     'jobs__name' => 'Càrrec',
     *     'idjob' => 'Càrrec',
     *  ]);
     * 
     *  $data['output'] = $crud->render();              // renders view
     *  return view('sample', $data);
     * </pre>
     *
     * @return void
     * 
     * @version 1.3
     */
    public function demo_relation1N()
    {
        $crud = new KpaCrud();


        $crud->setTable('workers');
        $crud->setPrimaryKey('id');

        /**
         * setRelation(field_name, related_table_name, related_table_field_name, related_field_to_display=null )
         * 
         * You can create a relation with two tables with setRelation funcion. 
         * Sample:
         *     Table 'workers' has a field name 'idjob'
         *     Table 'jobs' has a field 'id' that is de foreign key for idjob
         *     Table 'jobs' has also 'name' column that Crud will show in edit/delete/view screens
         * 
         *     related_field_to_display is optional, if it's null Crud will show related_table_field
         */
        $crud->setRelation('idjob', 'jobs', 'id', 'name');

        /**
         *  To show in list or trash screen a related field, you can load with 
         *  setColumns, the name will be 'relatedTableName__relatedTableFieldName
         *  Sample:
         *      List & trash screen will show id,name,surname and the job name stored into jobs table
         */
        $crud->setColumns(['id', 'name', 'surname', 'jobs__name']);

        /**
         * setConfig(config_array) or setConfig(config_collection_name)
         * 
         * This function permits to set parametres to configure your CRUD, you
         * can call function to set parameters individually if you need
         * 
         */

        $crud->setConfig('onlyView');
        $crud->setConfig(["editable" => false,]);

        $crud->setColumnsInfo([
            'name' => 'Nom',
            'surname' => 'Cognom',
            'jobs__name' => 'Càrrec',
            'idjob' => 'Càrrec',
        ]);

        $data['output'] = $crud->render();

        $data['title'] = 'Demo relation 1N';
        return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
    }
/**
     * demo_relation1N - Demo to shows how to get a table CRUD pages, where table has a unique relation
     * 
     * <pre>
     *  $crud = new KpaCrud();                          // loads default configuration    
     * 
     *  $crud->setTable('treecat');                        // set table name
     * 
     *  $crud->setPrimaryKey('id');                     // set primary key
     * 
     *  $crud->setRelation('pare', 'treecat', 'id', 'desc');
     * 
     *  $crud->setColumns(['desc', 'treecat__desc']);
     * 
     *  $crud->setColumnsInfo([
     *     'treecat__desc' => 'Parent',
     *  ]);
     * 
     *  $data['output'] = $crud->render();              // renders view
     *  return view('sample', $data);
     * </pre>
     *
     * @return void
     * 
     * @version 1.4.5
     */
    public function demo_selfrelation1N()
    {
        $crud = new KpaCrud();
        $crud->setTable('treecat');
        $crud->setPrimaryKey('id');

        $crud->setRelation('pare', 'treecat', 'id', 'desc');

        $crud->setColumns(['desc', 'treecat__desc']);
        
       $crud->setColumnsInfo([
          'treecat__desc' => 'Parent',
       ]);
        $data['output'] = $crud->render();

        $data['title'] = 'Demo self- relation 1N';
        return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
    }
}
