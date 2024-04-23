<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['login'] = 'welcome/login';
$route['dashboard'] = 'welcome/dashboard';
$route['transactions'] = 'welcome/transactions';
$route['psheets'] = 'welcome/psheets';
$route['fstocks'] = 'welcome/fstocks';
$route['syncstocksheets'] = 'welcome/syncstocksheets';
$route['specific_feed'] = 'welcome/specific_feed';
$route['undofreeze'] = 'welcome/undofreeze';
$route['freeze'] = 'welcome/freeze';
$route['stocks'] = 'welcome/stocks';
$route['specific_category'] = 'Welcome/specific_category';
$route['uncounted_category'] = 'Welcome/uncounted_category';
$route['fsheets'] = 'welcome/fsheets';
$route['pass_reset'] = 'Welcome/pass_reset';
$route['register'] = 'Welcome/register';
$route['stock_take'] = 'Welcome/stock_take';
$route['sync_stocks'] = 'Welcome/sync_stocks';
$route['remove/(:any)'] = 'Welcome/remove/$1';
$route['del_sheet_entry/(:any)'] = 'Welcome/del_sheet_entry/$1';
$route['product'] = 'Welcome/product';
$route['products'] = 'Welcome/products';
$route['stock_sheets'] = 'Welcome/stock_sheets';
$route['post_sheets'] = 'Welcome/post_sheets';
$route['holdings'] = 'Welcome/holdings';
$route['stocksposting'] = 'welcome/stocksposting';
$route['post_stocks'] = 'welcome/post_stocks';
$route['post_counted'] = 'welcome/post_counted';
$route['customsearch'] = 'welcome/customsearch';
$route['binsheets'] = 'welcome/binsheets';
$route['get_categories_department'] = 'Welcome/get_categories_department';
$route['get_subcategories_department'] = 'Welcome/get_subcategories_department';
$route['history'] = 'welcome/history';
$route['code_desc'] = 'welcome/code_desc';
$route['updatecode'] = 'welcome/updatecode';
$route['cancelcode'] = 'welcome/cancelcode';
$route['historysearch'] = 'welcome/historysearch';
$route['stocktakedetails/(:any)'] = 'welcome/stocktakedetails/$1';
$route['historysearch'] = 'welcome/historysearch';
$route['import_sheets'] = 'welcome/import_sheets';
$route['importData'] = 'welcome/importData';
$route['updatedetail'] = 'welcome/updatedetail';
$route['update_password'] = 'welcome/update_password';
$route['uncounted'] = 'welcome/uncounted';
$route['users'] = 'welcome/users';
$route['user'] = 'welcome/user';
$route['items'] = 'welcome/items';
$route['departments'] = 'welcome/departments';
$route['suppliers'] = 'welcome/suppliers';
$route['customers'] = 'welcome/customers';
$route['customeredit(:any)'] = 'Welcome/customeredit/$1';
$route['category_search'] = 'Welcome/category_search';
$route['sheetsdepartment_status'] = 'Welcome/sheetsdepartment_status';

/**Excel files*/
$route['excel'] = 'welcome/excel';
$route['countedexcel'] = 'welcome/countedexcel';
$route['binexcel'] = 'welcome/binexcel';
$route['customexcel'] = 'welcome/customexcel';
$route['historyexcel'] = 'welcome/historyexcel';
$route['detailsexcel/(:any)'] = 'welcome/detailsexcel/$1';
$route['synchronize'] = 'welcome/synchronize';
$route['logout'] = 'welcome/logout';
