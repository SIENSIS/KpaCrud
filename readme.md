- [KpaCrud Library](#kpacrud-library)
- [Install](#install)
  - [Install with composer](#install-with-composer)
  - [Install manually](#install-manually)
- [Publish command](#publish-command)
- [Constructor](#constructor)
- [Routes file](#routes-file)
- [Config file parameters](#config-file-parameters)
- [Method setConfig](#method-setconfig)
- [Method setTable](#method-settable)
- [Method setPrimaryKey](#method-setprimarykey)
- [Method setColumns](#method-setcolumns)
- [Method setRelation](#method-setrelation)
- [Method addWhere](#method-addwhere)
- [Method limit](#method-limit)
- [Method setColumnsInfo](#method-setcolumnsinfo)
  - [Available parameters](#available-parameters)
  - [Set field name](#set-field-name)
  - [Field types and samples](#field-types-and-samples)
    - [Number field type](#number-field-type)
    - [Range field](#range-field)
    - [Make a field **required**](#make-a-field-required)
    - [Make a field **Invisible**](#make-a-field-invisible)
    - [Checkbox field](#checkbox-field)
    - [Date field with default value in Add page](#date-field-with-default-value-in-add-page)
    - [Datetime field with default value in Add page](#datetime-field-with-default-value-in-add-page)
    - [Password field type](#password-field-type)
    - [Dropdown field type](#dropdown-field-type)
- [Method hideHeadLink](#method-hideheadlink)
- [Method addPostAddCallBack and addPostEditCallBack](#method-addpostaddcallback-and-addposteditcallback)
- [Functions isViewMode, isExportMode, isAddMode, isEditMode, isDelMode, isTrashMode](#functions-isviewmode-isexportmode-isaddmode-iseditmode-isdelmode-istrashmode)
- [Method addItemFunction](#method-additemfunction)
- [Method addItemLink](#method-additemlink)
- [Library Exceptions](#library-exceptions)

# KpaCrud Library

- [Samples and how to's](samples.md)
- [Change list](changes.md)
- [TODO](todo.md)
  
# Install 

## Install with composer

**Option 1:**

You need to add `SIENSIS/KpaCrud` require with composer tool.

```dos
> composer require siensis/kpacrud:dev-master
```

**Option 2:**

or modify `composer.json` and add the package to require items into `composer.json`

```json
    "require": {
        "siensis/kpacrud": "dev-master"
    },
```
**Finally**

Execute `composer update` command to update your project settings

```dos
> composer update
```

> :bulb: **Idea**
> 
> If you have any  problem, probably you need to update you composer, executing:
> 
> composer self-update --2

## Install manually

Download KpaCrud project and extract into your project in a ThirdPary folder, with this structure:

> :file_folder: app
> 
> :file_folder: public
> 
> :file_folder: tests
> 
> :file_folder: vendor
>  
> :file_folder: ThirdParty
> 
> |---- :file_folder:  SIENSIS
> 
> |---- |---:file_folder: KpaCrud


Then you need modify autoload config file `app/Config/Autoload.php` and add your new PSR4 package path.

```php
public $psr4 = [
    APP_NAMESPACE => APPPATH, // For custom app namespace
    'Config'      => APPPATH . 'Config',
    'SIENSIS\KpaCrud' => ROOTPATH . 'ThirdParty'.DIRECTORY_SEPARATOR.'SIENSIS'.DIRECTORY_SEPARATOR.'KpaCrud'.DIRECTORY_SEPARATOR.'src'
];
```

> :bulb: **Idea**
> 
> Constant **`DIRECTORY_SEPARATOR`** is used to prevent path problems in Linux or Windows servers
> 
# Publish command

`KpaCrud` has their command `kpacrud:publish` to generate automatically a sample controller, a view sample, a custom config file and the KpaCrud lang files into your App folder.

To generate this files, you need to execute this command:

```dos
> php spark kpacrud:publish
Publish demo Controller? [y, n]:
Publish Views? [y, n]:
Publish Config file? [y, n]:
Publish Language file? [y, n]:
```

If files already exists, publish command ask you for confirmation. Otherwise if you sure to overwrite files, you can call `kpacrud:publish` with `-f` option, like:

```dos
> php spark kpacrud:publish -f
```

# Constructor

You can save all config parameters into `KpaCrud.php` file in `App\Config` folder. 

```php
$crud = new KpaCrud(); //loads default configuration
```
Loads default parameters with `configDefaultName` as the config collection parameters. If you would like to load another collection, you can indicate it in constructor.

With this sample you KpaCrud library loads `listView` defined parameters.
```php
$crud = new KpaCrud('listView'); //loads listView configuration
```
# Routes file

KpaCrud library works with GET and POST methods, you need to create a GET and POST route to your controller function.

```php
$routes->match(['get','post'],'/route/to/crud', 'SampleKpaCrudController::demo_function');
```
POST Method is used by KpaCrud in `Add submit`, `Delete confirm` and `Edit submit`.

# Config file parameters
In the file `App\Config\KpaCrud.php` you can can store parameters collections identified with a name and the collection parameter used as default.


| Param name         |  Type   |   Default    | Description                                                                                                                 |
| ------------------ | :-----: | :----------: | --------------------------------------------------------------------------------------------------------------------------- |
| editable           | boolean |     true     | Defines if row has edit button                                                                                              |
| removable          | boolean |     true     | Defines if row has delete button                                                                                            |
| **Table tools**    |
| lang               | string  |              | Defines the URL of JS file language for Datatables JQuery tool                                                              |
| sortable           | boolean |     true     | Defines if table has enabled the sortable feature                                                                           |
| filterable         | boolean |     true     | Defines if table has enabled the searching tool                                                                             |
| paging             | boolean |     true     | Defines if table has enabled the paging tools                                                                               |
| numerate           | boolean |     true     | Defines library numerate rows                                                                                               |
| **Table features** |
| pagingType         | string  |              | Determines the paging type, values are: `numbers`, `simple`, `simple_numbers`, `full`, `full_numbers`, `first_last_numbers` |
| defaultPageSize    |   int   |              | Determines the page size set as default                                                                                     |
| rememberState      | boolean |    false     | Defines if table remembers last order column, search, etc                                                                   |
| **Right toolbar**  |
| add_button         | boolean |     true     | Enables add button in top right toolbar                                                                                     |
| show_button         | boolean |     true     | Enables show button in list items                                                                                          |
| recycled_button    | boolean |     true     | Enables trash buttons in top right toolbar (Empty trash, show trash)                                                        |
| exportXLS          | boolean |     true     | Enables export XLS button in top right toolbar                                                                              |
| print              | boolean |     true     | Enables print button in top right toolbar                                                                                   |
| **Left toolbar**   |         |              |
| multidelete        | boolean |     true     | Enables the multi select feature in table list to remove item or to move to trash if softDelete is enabled                  |
| deletepermanent    | boolean |     true     | Enables the multi select feature in table list to remove item permanently if softDelete is enabled                          |
| **Model features** |         |              |
| useSoftDeletes     | boolean |     true     | Enables the soft delete feature, then items are mark as delete and they can use trash view                                  |
| showTimestamps     | boolean |    false     | Enables to show fields created_at and updated_at in view page                                                               |
| createdField       | string  | 'created_at' | Name of created_at field into database                                                                                      |
| updatedField       | string  | 'updated_at' | Name of update_at field into database                                                                                       |
| deletedField       | string  | 'deleted_at' | Name of deleted_at field into database                                                                                      |

In config file you can define the default collection with `configDefaultName`.

The KpaCrud config file provided, are defined `onlyView`, `listView` and `default` (acts as fullView ).

# Method setConfig

You can set config parameters after object Library is created. The function setConfig can change all config parameters if you set a collection name as a parameter.

```php
$crud->setConfig('onlyView');
```
even, you can change only a [parameter](#config-file-parameters), like:

```php
$crud->setConfig(['editable'=>false]);                     // Sets editable configuration to false
$crud->setConfig(['editable'=>false,'removable'=>false]);  //Sets editable and removable config parameter to false 
```

See also [How to create your custom App\KpaCrud config file](samples.md#how-to-create-your-custom-appkpacrud-config-file)

# Method setTable

This method sets table name to generate CRUD pages, when you set table name, method can detect primary key. By default, `setTable` doesn't detect primary key.
To load automatically primary key, you need to set `true` the `loadPrimaryKeys` function flag, like this sample:
```php
$crud->setTable('news', true);    // Primary key autoload feature
```

otherwise, you can only specify table name.

```php
$crud->setTable('news');
```

# Method setPrimaryKey

This method adds primary key to CRUD Library, you can use it if you doesn't use 
automatic primary key load. You can call function for every key, if your table has more than
a primary key.

```php
$crud = new KpaCrud();

$crud->setTable('tokens');
$crud->setPrimaryKey('tokenid');
$crud->setPrimaryKey('subject');
```

> [:warning: Exception](#library-exceptions)
> 
> If the primary key string field doesn't exists, method will throw an exception

# Method setColumns

This method will permit to set columns that will be shown in CRUD Page view or CRUD Trash view if enabled. 

```php
$crud = new KpaCrud('listView');        // loads listView configuration
$crud->setTable('news');                // load news table
$crud->setPrimaryKey('id');             // set primary key to id field
$crud->setColumns(['id', 'title', 'data_pub']); // set fields to show in listView
```

> [:warning: Exception](#library-exceptions)
> 
> Function throws this 
> - Table is null or not defined
> - Table doesn't exists in database
> - Field doesn't exists in table
# Method setRelation

With this method you can set a relation 1=>N from a table to another one, for CRUD operations.

```php
  $crud = new KpaCrud('listView');                          // loads listView configuration    
 
  $crud->setTable('auth_groups_users');                     // set table name
 
  $crud->setPrimaryKey('group_id');                         // set primary key
  $crud->setPrimaryKey('user_id');                          // set primary key
 
 // function setRelation($fieldName, $relatedTable, $relatedField, $display_as = null)

 // display_as is the column name to show in edit / view mode
 // if not set, relatedfield is shown
  $crud->setRelation('group_id', 'auth_groups', 'id', 'name');
  $crud->setRelation('user_id', 'users', 'id', 'username');
```
The display_as parameter is to indicated the field name from related table, to show instead relatedField. If parameter is null, will show first upper case related field name

They can display more than a relation, like example.
# Method addWhere  

This method permits to filter data show in the KpaCrud admin table. You can 
set filter as an associative array, or you can set SQL where expression as string. 

The third parameter permits you to use OR conjuntion in a where clause

```php
    $crud->addWhere ("id","3");
    $curd->addWhere ("id","4", true);
```
This clause generates 
```sql
WHERE id=3 OR id=4
```

>:warning: **WARNING!!**
>
>If you use parameters with this function, you need to check it to avoid SQL injection
# Method limit  
This method adds limit clause to database query, usefull to show limited data. Sintax as codeigniter querybuilder limit function
```php
  $crud->limit(10);   // Produces: LIMIT 10

  $crud->limit(10, 20);  // Produces: LIMIT 20, 10
```

# Method orderBy  
This method Adds order by clause to database query, usefull to show ordered data. Sintax as codeigniter querybuilder limit function
```php
  $crud->orderBy('title', 'DESC');  // Produces: ORDER BY `title` DESC

  $crud->orderBy('title DESC, name ASC');  // Produces: ORDER BY `title` DESC, `name` ASC

  $crud->orderBy('title', 'DESC');  $builder->orderBy('name', 'ASC');   // Produces: ORDER BY `title` DESC, `name` ASC

  $crud->orderBy('title', 'RANDOM');  // Produces: ORDER BY RAND()

  $crud->orderBy(42, 'RANDOM');  // Produces: ORDER BY RAND(42)
```

# Method setColumnsInfo

## Available parameters

The function setColumnsInfo permits to customize every database field.


| Parameter     |         Type          | Description                                                                                                                                                                                                                                                |
| ------------- | :-------------------: | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| name          |       `string`        | Field name to show user in pages                                                                                                                                                                                                                           |
| type          |       `string`        | Availables field types are: DEFAULT_FIELD_TYPE, INVISIBLE_FIELD_TYPE, EMAIL_FIELD_TYPE, CHECKBOX_FIELD_TYPE,NUMBER_FIELD_TYPE, RANGE_FIELD_TYPE, DATE_FIELD_TYPE, DATETIME_FIELD_TYPE, TEXTAREA_FIELD_TYPE [(Check field types)](#field-types-and-samples) |
| default       |       `string`        | Field default value in add page                                                                                                                                                                                                                            |
| check_value   | `string,bool,integer` | Value stored when a checkbox is checked. __DEFAULT=1__                                                                                                                                                                                                     |
| uncheck_value | `string,bool,integer` | Value stored when a checkbox is unchecked. __DEFAULT=0__                                                                                                                                                                                                   |
| html_atts     |    `array[string]`    | Others html attributes to add to field, like: required, placeholder, pattern, title, min, max, step...                                                                                                                                                     |
| options       |        `array`        | Options to show in a dropdown field                                                                                                                                                                                                                        |
| excludes      |        `array`        | Values that are excluded to show into field                                                                                                                                                                                                                |

## Set field name


You can set the field name with the properties associative array like this sample:
```php
'dbfieldname' => [
    'name' => 'Field name to show',
],
```
or you can set it directly, but in this last version, you can only set the name for a db field.
```php
'dbfieldname' => 'Field name to show',
```

## Field types and samples
### Number field type

```php
'dbfieldname' => [
    'name' => 'Demo number field',
    'type' => KpaCrud::NUMBER_FIELD_TYPE,
    'default' => '25',
    'html_atts' => [
        'min="1"',
        'max="50"',
    ]
],
```

### Range field

```php
'dbfieldname' => [
    'name' => 'Demo text field',
    'type' => KpaCrud::RANGE_FIELD_TYPE,
    'default' => '25',
    'html_atts' => [        // html_atts are optional, but useful to costumize page
        'min="1"',
        'max="50"',
        'step="5"',
    ]
],
```

### Make a field **required**

```php
'dbfieldname' => [
    'name' => 'Demo text field',
    'html_atts' => [
        "required", 
        "placeholder=\"Add your info here\""
    ],
],
```

### Make a field **Invisible**

This field will be invisible in all views

```php
'dbfieldname' => [
    'type' => KpaCrud::INVISIBLE_FIELD_TYPE
],
```

### Checkbox field

```php
'dbfieldname' => [
    'name' => 'Demo text field',
    'type' => KpaCrud::CHECKBOX_FIELD_TYPE, 
    'check_value' => '1',   // By default check_value=1. You can omit it is equal
    'uncheck_value' => '0'  // By default uncheck_value=0    
],
```


### Date field with default value in Add page

```php
'dbfieldtype' => [
    'type' => KpaCrud::DATE_FIELD_TYPE,
    'default' => '1-2-2022'  // you can set default date for add page
],
```

### Datetime field with default value in Add page

```php
'dbfieldtype' => [
    'type' => KpaCrud::DATETIME_FIELD_TYPE,
    'default' => '1-2-2022 15:43'  // you can set default date for add page
],
```
### Password field type

This type permits to hide content data in edit page or new page. All fields typed as password are always hidden in view or delete views.

Password field type in the edit page adds a hidden form field to check programatically if user has changed the value. This feature permits you to combine with edit and new callback to store passwords hashed in you DB.

See [Samples file](samples.md)

### Dropdown field type

You can create a custom dropdown item, to control values introduced in a field by user. You can define dropdown values with an associative array. 

To make an identic checkbox with a dropdown, you can set item-value only, like this.
<table><tr><td><pre>
'active' => [
    'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
    'options' => ["Disabled", "Active"],
    'html_atts'=>[
        "required",
    ]
],
</pre></td><td><pre>
'active' => [
    'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
    'options' => ["0"=>"Disabled", "1"=>"Active"],
    'html_atts'=>[
        "required",
    ]
],
</pre></td></tr></table>

This samples generates
```html
<select name="data_active" id="data_active" required="">
    <option value="0">Disabled</option>
    <option value="1">Active</option>
</select>
```

If you need to show a select value item in a dropdown, you can do it easily adding a null index item. Like this sample.

```php
'active' => [
    'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
    'options' => [""=>"Select option","Disabled","Active"],
    'html_atts'=>[
        "required",
    ]
],
```
This samples generates
```html
<select name="data_active" id="data_active" required="">
    <option value="" selected="selected">Select option</option>
    <option value="0">Disabled</option>
    <option value="1">Active</option>
</select>
```
# Method hideHeadLink
If you need to hide CSS/JS from head, you can use `hideHeadLink` function. Every link has its own id, the availables ones are:


| ID                  | Description                            |
| ------------------- | -------------------------------------- |
| js-query            | JQuery Javascript CDN                  |
| js-bootstrap        | Bootstrap JS file                      |
| js-datatables       | Datatables plugin JS file              |
| js-datatables-boot  | Datatables JS file for bootstrap theme |
| css-bootstrap       | Bootstrap CSS file                     |
| css-datatables-boot | Datatables CSS for bootstrap theme     |
| css-fontawesome     | Fontawesome CSS file                   |

See ["How to change bootstrap, jquery or CSS/JS head links"](samples.md#how-to-change-bootstrap-jquery-or-cssjs-head-links) for a sample.

# Method addPostAddCallBack and addPostEditCallBack

If you need to get post data to change anythig before `KpaCrud` uses this info to store it in database, you need to set an add or edit callback. 

The function will receive post data and they must return post data modified or **`null`** if you need to cancel event, in this case `KpaCrud` showns a cancel message like: **` Error callback function cancel operation`**

Post data fields will use the name according this structure: 

> data_ + DATABASE_FIELD_NAME

See ["How to set a callback to store hashed password"](samples.md#how-to-set-a-callback-to-store-hashed-password) for a sample.

# Functions isViewMode, isExportMode, isAddMode, isEditMode, isDelMode, isTrashMode

The functions functions `isViewMode`, `isExportMode`, `isAddMode`, `isEditMode`, `isDelMode`, `isTrashMode` permits you to know KpaCrud mode, to customize KpaCrud aspect.

**Sample**: You need to export all database fields but in list mode you would like to show only id and description fields.

See 
["How to custom parameters according KpaCrud view mode"](samples.md#how-to-custom-parameters-according-kpacrud-view-mode) for a sample.

# Method addItemFunction

This function permits to declare a new icon function in every register. The function used as callback may be defined in your controllers.

The callback function used may returns a view as string. KpaCrud library uses this html information to show them in KpaCrud interface.

If you need a function to be called afterly, as post page or other like this, you can  define invisible functions.

See 
[How to add a function for every register](samples.md#how-to-add-a-function-for-every-register) for a sample.
# Method addItemLink 
This function permits to declare a new icon link for every register. The function used as callback may be defined in your controllers.

```php
        $crud->addItemLink('view', 'fa-file-o', base_url('route/to/link'), 'Tooltip for icon button');
        // GENERATES <a href="ROUTE/[IDs_SEPARETED_BY_SLASH]" title="HELP TEXT">ICON</a>

        $crud->addItemLink('show', 'fa-file-o', [base_url('route/to/link'),'hash'], 'Tooltip for icon button'));
        // GENERATES <a href="ROUTE/[IDs_SEPARETED_BY_SLASH_HASHED]" title="HELP TEXT">ICON</a>

```
# Library Exceptions


| Exception ID | Exception                 |                                                                                                   |
| ------------ | ------------------------- | ------------------------------------------------------------------------------------------------- |
| 1, 4, 8      | Table name is null        |                                                                                                   |
| 2, 5, 9      | Table not exists in DB    |                                                                                                   |
| 3, 6         | Field name not exists     | You try to set a field name as ID or show as a column in list view, and this field doesn't exists |
| 7            | Field type unknown        | Check available field types in documentation                                                      |
| 10           | ID Field name set to null |                                                                                                   |

