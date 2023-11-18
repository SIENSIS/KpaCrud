- [Sample simple table CRUD](#sample-simple-table-crud)
- [Sample simple table customized (addWhere + setConfig)](#sample-simple-table-customized-addwhere--setconfig)
- [How to custom columns with setColumnsInfo](#how-to-custom-columns-with-setcolumnsinfo)
- [How to set table multikey](#how-to-set-table-multikey)
- [How to set relation 1<=>N](#how-to-set-relation-1n)
- [How to set relation N<=>M](#how-to-set-relation-nm)
- [How to change bootstrap, jquery or CSS/JS head links](#how-to-change-bootstrap-jquery-or-cssjs-head-links)
- [How to set a callback to store hashed password](#how-to-set-a-callback-to-store-hashed-password)
- [How to custom parameters according KpaCrud view mode](#how-to-custom-parameters-according-kpacrud-view-mode)
- [How to add a function for every register](#how-to-add-a-function-for-every-register)
- [How to create your custom App\KpaCrud config file](#how-to-create-your-custom-appkpacrud-config-file)
- [How to create your custom App\KpaCrud config file (manually)](#how-to-create-your-custom-appkpacrud-config-file-manually)


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

# How to set a callback to store hashed password

You need to declare the callback function, like:

```php
    /* hashNewPassword - used to store hash password in add form */
    public function hashNewPassword($postData)
    {
        $postData['data_password_hash'] = password_hash($postData['data_password_hash'], PASSWORD_DEFAULT);
        return $postData;
    }
    /* hashEditPassword - used to store hashed password if user change it in edit form */
    public function hashEditPassword($postData)
    {
        if($postData['data_password_hash']!='') {
            // field has a new value. You new to generate new password
            $postData['data_password_hash'] = password_hash($postData['data_password_hash'], PASSWORD_DEFAULT);
        } else { field not changed, you needn't to update
            unset($postData['data_password']);
        }
        return $postData;
    }
```
In this sample, database field name is `password` then post data field is `data_password`.

Then you need to set the crud callback in your controller.

```php
    $crud = new KpaCrud(); //loads default configuration

    $crud->setTable('users');
    $crud->setPrimaryKey('id');

    $crud->addPostAddCallBack(array($this, 'hashNewPassword'));
    $crud->addPostEditCallBack(array($this, 'hashEditPassword'));
```

# How to custom parameters according KpaCrud view mode

You can customize KpaCrud aspect according KpaCrud view mode (list, add, del, export, print...), to do this you need to use functions `isViewMode`, `isExportMode`, `isAddMode`, `isEditMode`, `isDelMode`, `isTrashMode`.

```php
$crud = new KpaCrud(); 

$crud->setTable('users');
$crud->setPrimaryKey('id');

if ($crud->isExportMode()){
    $crud->setColumns([ 'username','email','active']);
    $crud->addWhere('users.active=1');
} else {

    $crud->setColumns(['id', 'email', 'username']);
}
```

In the sample above KpaCrud exports columns `username`, `email` and `active` database field, in the order described, also the sample only exports or prints active users. Otherwise in other views (list, edit, add, trash...), showns all data without filtering and `id`, `email`, `username` with this order.  

# How to add a function for every register

The callback function used may returns a view as string. KpaCrud library uses this html information to show them in KpaCrud interface.

```php
 $crud = new KpaCrud(); //loads default configuration

$crud->setTable('users');
$crud->setPrimaryKey('id');

$crud->setColumns(['id', 'email', 'username']);

// Create an button icon in every register
$crud->addItemFunction('mailing', 'fa-paper-plane', array($this, 'myCustomPage'), "Send mail");

```

The callback function will receive all register information as associative array and they will return a view as string like:

```php
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
```

If you need to call a function as POST you need previously declare as invisible item callback, like:

```php
// Create an invisible named function in KpaCrud to call after
$crud->addItemFunction('mpost', '', array($this, 'myCustomPagePost'), "",false);

public function myCustomPagePost($obj)
{
    // $obj contains info about register if you repeat querystring received in MyCustomPage
    $html ='<h1>Operation ok</h1>';
    /*
    Do something with this->request->getPost information
    */
    return $html;
}
```
# How to create your custom App\KpaCrud config file 

You can use `kpacrud:publish` to generate automatically a controller sample, a config file to customize or the lang files to also customize it.

To generate this files, you need to execute this command:

```dos
> php spark kpacrud:publish
Publish demo Controller? [y, n]:
Publish Config file? [y, n]:
Publish Language file? [y, n]:
```

If files already exists, publish command ask you for confirmation. Otherwise if you sure to overwrite files, you can call `kpacrud:publish` with `-f` option, like:

```dos
> php spark kpacrud:publish -f
```

# How to create your custom App\KpaCrud config file (manually)

You can create your custom KpaCrud config file, following this steps:

1. Copy `SIENSIS\KpaCrud\Config\KpaCrud.php` file into you `App\Config` folder
2. Modify namespace to `namespace Config;`
3. Modify class definition to `class KpaCrud extends \SIENSIS\KpaCrud\Config\KpaCrud`

Now you have a custom config file where you can add new policies or change default ones independent of library updates.
