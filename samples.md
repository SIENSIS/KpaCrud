- [Sample simple table CRUD](#sample-simple-table-crud)
- [Sample simple table customized (addWhere + setConfig)](#sample-simple-table-customized-addwhere--setconfig)
- [How to custom columns with setColumnsInfo](#how-to-custom-columns-with-setcolumnsinfo)
- [How to set table multikey](#how-to-set-table-multikey)
- [How to set relation 1<=>N](#how-to-set-relation-1n)
- [How to set relation N<=>M](#how-to-set-relation-nm)
- [How to change bootstrap, jquery or CSS/JS head links](#how-to-change-bootstrap-jquery-or-cssjs-head-links)
- [How to set a callback](#how-to-set-a-callback)


# Sample simple table CRUD

```php
    $crud = new KpaCrud();                          // loads default configuration    
    $crud->setConfig('onlyView');                   // sets configuration to onlyView
                                                    // set into config file
    $crud->setTable('news');                        // set table name
    $crud->setPrimaryKey('id');                     // set primary key
    $crud->setColumns(['id', 'title', 'data_pub']); // set columns/fields to show
    $crud->setColumnsInfo([                         // set columns/fields name
            'id' => ['name'=>'Code'],
            'title' => ['name'=>'News titular'],
            'data_pub' => ['name'=>'Publication date','type'=>KpaCrud::DATETIME_FIELD_TYPE],
    ]);
    $crud->addWhere('news.data_pub>="2022-04-02 21:03:48"'); // show filtered data
    $data['output'] = $crud->render();              // renders view
    return view('sample', $data);
```

# Sample simple table customized (addWhere + setConfig)

```php
    $crud = new KpaCrud('listView'); //loads listView configuration

    /**
     * $crud = new KpaCrud('listView');  Loads KpaCrud listView defined in Config\KpaCrud.php 
     * 
     * $crud->setConfig('onlyView');  Set all KpaCrud configuration to onlyView defined
     * in Config\KpaCrud.php file 
     */

    /**
     * $crud->setTable('news', true);    // Primary key autoload feature 
     * 
     * or manual primary key set
     */
    $crud->setTable('news');
    $crud->setPrimaryKey('id');

    /**
     * setColumns([column_name1,column_name2,column_name2])
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

    $crud->setConfig('onlyView');               // Loads onlyView collection
    $crud->setConfig(["editable" => false,]);   // set editable config parameter to false

    $data['output'] = $crud->render();

    return view('\SIENSIS\KpaCrud\Views\sample\sample', $data);
```

# How to custom columns with setColumnsInfo

```php

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
        'id' => ['name' => 'Code id'],
        'email' => [
            'name' => 'eMail', 
            'html_atts' => [
                'required',
                'placeholder="Introduce email address"'
            ], 
            'type' => KpaCrud::EMAIL_FIELD_TYPE
        ],
        'username' => [
            'name' => 'User name',
            'type' => KpaCrud::TEXTAREA_FIELD_TYPE,
            'html_atts' => [
                "required", 
                "placeholder=\"Introduce user name\""
            ],
        ],
        // 'active' => [
        //     'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
        //     'options' => ['' => "Select option", 'User disabled', 'User active'],
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
            'default'=>'1',
            'check_value' => '1',
            'uncheck_value' => '0'
        ],

        // 'force_pass_reset' => [
        //     'name' => 'Force reset password',
        //     'type' => KpaCrud::CHECKBOX_FIELD_TYPE,
        //     'default'=>'1',
        //     'check_value' => '1',
        //     'uncheck_value' => '0'
        // ],
        'force_pass_reset' => [
            'name' => 'Force reset password',
            'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
            'options' => ['' => "Select option", 'Password doesn\'t change', 'Change password'],
            'html_atts' => [
                "required",
            ],
            'default'=>'0',
        ],

        'reset_expires' => [
            'type' => KpaCrud::DATE_FIELD_TYPE,
            'default'=> date('Y-m-d', strtotime(date("d-m-Y"). ' + 6 days'))
            
        ],
        'activate_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'password_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'reset_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'reset_at' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'status' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'status_message' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],

    ]);
```



# How to set table multikey

```php
    $crud = new KpaCrud();

    $crud->setTable('tokens');
    $crud->setPrimaryKey('tokenid');
    $crud->setPrimaryKey('subject');
```

# How to set relation 1<=>N

```php
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
```

# How to set relation N<=>M

```php
    $crud = new KpaCrud('listView');

    $crud->setTable('auth_groups_users');
    $crud->setPrimaryKey('group_id');
    $crud->setPrimaryKey('user_id');

    /**
     * public function setRelation($fieldName, $relatedTable, $relatedField, $display_as=null)
     * 
     * $display_as => To set column name to show if not set, relatedfield is shown
     * 
     */
    $crud->setRelation('group_id', 'auth_groups', 'id', 'name');
    $crud->setRelation('user_id', 'users', 'id', 'username');

    $crud->setColumns(['auth_groups__name', 'users__username', 'users__email']);

    // To show a field for a related table, it's necessary to call
    // prefixed with tablename like: users__email => will show email from users table

    $crud->setColumnsInfo([
        'auth_groups__name' => 'Rol',
        'users__username' => 'Usuari',
        'users__email' => 'eMail',
    ]);
```

# How to change bootstrap, jquery or CSS/JS head links

If you would like to include links into the app view file, you need to hide all CSS+JS files with this call.
```php
$crud->hideHeadLink([
    'js-jquery', 
    'js-bootstrap',
    'js-datatables',
    'js-datatables-boot',
    'css-bootstrap',         
    'css-datatables-boot',
    'css-fontawesome'
]);
```

# How to set a callback

You need to declare the callback function, like:

```php
    public function hashPassword($postData){
        $postData['data_password']=password_hash($postData['data_password'], PASSWORD_DEFAULT);
        return $postData;
    }
```
In this sample, database field name is `password` then post data field is `data_password`.

Then you need to set the crud callback in your controller.

```php
    $crud = new KpaCrud(); //loads default configuration

    $crud->setTable('users');
    $crud->setPrimaryKey('id');

    $crud->addPostAddCallBack(array($this,'hashPassword'));
    $crud->addPostEditCallBack(array($this,'hashPassword'));
```