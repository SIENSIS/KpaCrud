# SIENSIS KpaCrud v1.4.5a Build 20220519

## Done
  - Add orWhere function
  - Fixed addRelation to permit self relations (Thanks N.Estudillo)
  - Add self relation sample into controller
  - Fixed publish command, to publish sample view

__Version 1.4.5a (Build:20220519)_


# SIENSIS KpaCrud v1.4.4a Build 20220518

## Done
  - Add `kpacrud:publish` command
  - Fixed config filename for Unix/Linux systems (Thanks J.Navarro)
  - Fixed minor errors

__Version 1.4.4a (Build:20220518)_

# SIENSIS KpaCrud v1.4.3a Build 20220513

## Done
  - Added custom function item
  - Added new samples
  - Fixed minor errors

__Version 1.4.3a (Build:20220513)_

# SIENSIS KpaCrud v1.4.2a Build 20220510

## Done
  
  - Added route readme sample
  - Added functions isViewMode, isExportMode, isAddMode, isEditMode, isDelMode, isTrashMode
  - Added cancel post event feature via callback in EditCallback and AddCallback

__Version 1.4.2a (Build:20220510)__

# SIENSIS KpaCrud v1.4.1a Build 20220506

## Done

- Fixed add/edit redirect
- Fixed configuration load problem if config file in app/config folder 
- Added add and edit callback
- Added a method that permits to hide some CSS+JS files
- Added password field type and PASSWORD_FIELD_TYPE constant

__Version 1.4.1a (Build:20220506)__

# SIENSIS KpaCrud v1.4a Build 20220503

## Done

- Added project to Github and packagist
- Added composer compatibility
- Added install instructions
  
__Version 1.4a (Build:20220502)__

# SIENSIS KpaCrud v1.3.0.2a Build 20220502

## Done

- Added globally TODO and changes.log file in markdown format
- Added SQL_SEPARATOR as constant KpaCrud 
- Added DEFAULT_FIELD_TYPE field type as constant as KpaCrud
- Added INVISIBLE_FIELD_TYPE field type as constant as KpaCrud
- Added CHECK_VALUE as constant KpaCrud
- Added UNCHECK_VALUE as constant KpaCrud
- Added custom type in setColumnInfo (email, invisible)
- Added email type with validation
- Added javascript validation inputs
- Added sample controller and views intro KpaCrud src
- Added extra html atts to add and edit view. Ex: required, pattern, placeholder, min, max..
- Added number field type and NUMBER_FIELD_TYPE constant
- Added range field type and RANGE_FIELD_TYPE constant
- Added date and datetime field type, DATE_FIELD_TYPE and DATETIME_FIELD_TYPE constant
- Added textarea field type, TEXTAREA_FIELD_TYPE constant
- Added helper function str_ends_with (PHP 8 incorpores this function)
- Added dropdown field type
 
## Fixed
- Fixed created_at date when add item
- Fixed configDefaultName variable error. (Thanks A.Carrillo)
- Fixed error when error in ADD item. Now showns error and filled erroneous form
- Fixed field type in setColumnsInfo, throwns exception if type value no valid
- Fixed minor errors 

__Version 1.3.0.2a (Build:20220502)__

# SIENSIS KpaCrud v1.3.0.1a Build 20220412


## Done

- Added where filter when select info from a table 
- Updated KpaCrud documentation
- Added documentation samples 
- Added config profile with full features 
- Added view to sample 
- Added and documented TODOs and DONE tasks
- Added phpdoc.dist.xml

## Fixed

- Fixed minor errors 

__Version 1.3.0.1a (Build:20220412)__


# SIENSIS KpaCrud v1.3a Build 20220408

## Done 
- KpaCrud configuration in config files
- Added sample controller 
- Added doc config 
- Added doc sample controller 
- Added sample config 
## Fixed
- Fixed minor errors 
 
__Version 1.3a Build 20220408__


# SIENSIS KpaCrud v1.2a Build 20220407
 
## DONE
  - KpaCrud library 
 
__Version 1.2a Build 20220407__
