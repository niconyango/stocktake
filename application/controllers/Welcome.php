<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Collection\Memory;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


#[\AllowDynamicProperties]
class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        //error_reporting(0);
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
        // $this->load->library("session");
        // $this->load->helper('url');
    }

    /** Loading landing page */
    public function index($data = NULL)
    {
        $data['title'] = 'Login';

        $this->load->view('common/header', $data);
        $this->load->view('welcome');
        //$this->load->view('common/footer');
    }

    /** Session creation method */
    public function login()
    {
        $data['Number'] = $this->security->xss_clean($this->input->post('username'));
        $data['Pass'] = $this->security->xss_clean($this->input->post('password'));
        $result = $this->Stocktake->login($data);
        if (!empty($result)) {
            if (($result->SecurityLevel === NULL) || ($result->SecurityLevel === 0)) {
                $data["error"] = "Unknown User level";
                $this->index($data);
            } else {
                $session_data = array(
                    'ID' => $result->ID,
                    'LastUpdated' => $result->LastUpdated,
                    'Number' => $result->Number,
                    'StoreID' => $result->StoreID,
                    'Name' => $result->Name,
                    'FloorLimit' => $result->FloorLimit,
                    'ReturnLimit' => $result->ReturnLimit,
                    'DropLimit' => $result->DropLimit,
                    'CashDrawerNumber' => $result->CashDrawerNumber,
                    'SecurityLevel' => $result->SecurityLevel,
                    'EmailAddress' => $result->EmailAddress,
                    'FailedLogonAttempts' => $result->FailedLogonAttempts,
                    'MaxOverShortAmount' => $result->MaxOverShortAmount,
                    'MaxOverShortPercent' => $result->MaxOverShortPercent,
                    'OverShortLimitType' => $result->OverShortLimitType,
                    'Telephone' => $result->Telephone,
                    'Enabled' => $result->Enabled,
                    'TimeSchedule' => $result->TimeSchedule,
                    'LastPasswordChange' => $result->LastPasswordChange,
                    'PassExpires' => $result->PassExpires,
                    'InventoryLocation' => $result->InventoryLocation,
                    'SalesRepID' => $result->SalesRepID,
                    'BinLocation' => $result->BinLocation,
                    'Signature' => $result->Signature,
                    'logged' => TRUE,
                );

                $this->session->set_userdata($session_data);
                $data['main_content'] = 'Login successful';
                $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
                if ($this->session->userdata('SecurityLevel') == 19 || $this->session->userdata('SecurityLevel') == 5) {
                    if ($data['stocktakestatus'] == 0) {
                        redirect('fstocks', $data);
                    } else {
                        redirect('stocks', $data);
                    }
                } else {
                    if ($data['stocktakestatus'] == 0) {
                        $data["error"] = "No Stock Take in Progress.";
                        $this->index($data);
                    } else {
                        redirect('fsheets', $data);
                    }
                }
            }
        } else {

            $data["error"] = "Incorrect username or password.";
            $this->index($data);
        }
    }

    // Dashboard method.
    public function dashboard()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Dashboard';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['progress'] = $this->Stocktake->sheetprogress();
            //$data['department'] = $this->Stocktake->departmentalprogress();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('dashboard');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Dashboard
    public function sheetsdepartment_status()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $data['departments'] = $this->Stocktake->departmentalprogress();
                echo json_encode($data);
                break;
            default:
                redirect('welcome');
                break;
        }
    }

    /**Existing system users */
    public function users()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Users';
            $data['users'] = $this->Stocktake->system_users();
            $data['userlevels'] = $this->Stocktake->userlevels();
            $data['branches'] = $this->Stocktake->branches();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('users');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Adding a new user method.
    public function user()
    {
        $save = $this->Stocktake->user();
        if ($save == 1) {
            echo "User saved successully.";
        } else if ($save == 2) {
            echo "User updated successully.";
        } else {
            echo "Error Saving.";
        }
    }

    /**List of the items */
    public function items()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Items';
            // departments,categories & subcategories.
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            $data['items'] = $this->Stocktake->items();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('items');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /**Department listing */
    public function departments()
    {
        if ($this->session->userdata('logged')) {
            // Header title
            $data['title'] = 'Departments';
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->departments();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('departments');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** List of all the suppliers */
    public function suppliers()
    {
        if ($this->session->userdata('logged')) {
            // Header title
            $data['title'] = 'Suppliers';
            $data['suppliers'] = $this->Stocktake->suppliers();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('suppliers');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // List of all the customers.
    public function customers()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Customers';
            $data['customers'] = $this->Stocktake->customers();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('customers');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /**  */
    public function transactions()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Stock Take Sheets';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['products'] = $this->Stocktake->transactions();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('sheets');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** */
    public function stock_sheets()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Print Sheet';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->psheets();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('sheets');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** */
    public function fstocks()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Stocks Freeze';
            $data['config'] = $this->Stocktake->storeconfig();
            $data['freeze'] = $this->Stocktake->stocktakestatus();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['stocktakeprogress'] = $this->Stocktake->stocktakeprogress();
            // departments,categories & subcategories.
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('stockfreeze');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Posting stock take page method.
    public function stocksposting()
    {
        if ($this->session->userdata('logged')) {
            // Header title
            $data['title'] = 'Post Stocks';
            $data['config'] = $this->Stocktake->storeconfig();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['pendingsyncrecords'] = $this->Stocktake->pendingsyncrecords();
            $data['pendings'] = $this->Stocktake->pending_tempsheets();
            $data['tempsheets_status'] = $this->Stocktake->tempsheets_status();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('poststocks');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /**  */
    public function products()
    {
        $id = $this->input->post('UserID');
        $data['entries'] = $this->Stocktake->products($id);
        echo json_encode($data);
    }

    // Posting all counted SKUs and reseting uncounted to Zero.
    public function post_stocks()
    {
        $save = $this->Stocktake->post_stocks();
        if ($save == 1) {
            echo "Stocks Posted successully.";
            redirect("stocks", "refresh");
        } else {
            echo "Error Posting Stocks.";
            redirect("stocksposting", "refresh");
        }
    }

    // Posting only counted SKUs only.
    public function post_counted()
    {
        $save = $this->Stocktake->post_counted();
        if ($save == 1) {
            echo "Stocks Posted successully.";
            redirect("stocks", "refresh");
        } else {
            echo "Error Posting Stocks.";
            redirect("stocksposting", "refresh");
        }
    }

    // Freezing stocks for a stock take.
    public function freeze()
    {
        $save = $this->Stocktake->fstocks();
        if ($save == 1) {
            echo "Stocks freezed successully.";
            redirect("fsheets", "refresh");
        } else {
            echo "Error Freezing Stocks.";
            redirect("fstocks", "refresh");
        }
    }

    // Bin numbers import interface.
    public function import_sheets()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Import Stock Sheets';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('importsheets');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // server side processing of holding stock items.
    public function fetch_holdings()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                // form data.
                $DepartmentID = $this->input->post('DepartmentID');
                $CategoryID = $this->input->post('CategoryID');
                $SubCategoryID = $this->input->post('SubCategoryID');
                // dataTable defined details.
                $start = $this->input->post('start', true);
                $length = $this->input->post('length', true);
                $search = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $records = $this->Stocktake->holdingscount();
                $totalFiltered = $this->Stocktake->filteredholdings($search, $DepartmentID, $CategoryID, $SubCategoryID);
                $entries = $this->Stocktake->holdings($search, $order_by, $order_dir, $start, $length, $DepartmentID, $CategoryID, $SubCategoryID);
                // prepare response.
                $data = [];
                foreach ($entries as $row) {
                    $data[] = [
                        $row->CountingDate,
                        $row->itemcode,
                        $row->Alias,
                        $row->Description,
                        $row->Cost,
                        $row->Price,
                        $row->OriginalQty,
                        $row->availableTotal,
                        $row->CountedQty,
                        $row->countedTotal
                    ];
                }
                //return json response
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => intval($this->input->post('draw')),
                    "recordsTotal" => $records,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
                break;
            default:
                redirect('welcome');
        }
    }

    // Available stocks before stock take.
    public function holdings()
    {
        if ($this->session->userdata('logged')) {
            // Header title
            $data['title'] = 'Current Stocks';
            $data['config'] = $this->Stocktake->storeconfig();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            //$data['freeze'] = $this->Stocktake->holdings();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('holdings');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // server side stocks processing.
    public function fetch_stocks()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                // form data.
                $DepartmentID = $this->input->post('DepartmentID');
                $CategoryID = $this->input->post('CategoryID');
                $SubCategoryID = $this->input->post('SubCategoryID');
                // dataTable defined details.
                $start = $this->input->post('start', true);
                $length = $this->input->post('length', true);
                $search = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $records = $this->Stocktake->stockscount();
                $totalFiltered = $this->Stocktake->filteredstocks($search, $DepartmentID, $CategoryID, $SubCategoryID);
                $entries = $this->Stocktake->stocks($search, $order_by, $order_dir, $start, $length, $DepartmentID, $CategoryID, $SubCategoryID);
                // prepare response.
                $data = [];
                foreach ($entries as $row) {
                    $data[] = [
                        $row->ItemID,
                        $row->CountedDate,
                        $row->Code,
                        $row->Alias,
                        $row->description,
                        $row->Cost,
                        $row->OriginalQty,
                        $row->availableTotal,
                        $row->CountedQty,
                        $row->countedTotal
                    ];
                }
                //return json response
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => intval($this->input->post('draw')),
                    "recordsTotal" => $records,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
                break;
            default:
                redirect('welcome');
        }
    }

    // Counted stocks records
    public function stocks()
    {
        if ($this->session->userdata('logged')) {
            // Header title
            $data['title'] = 'Stock Take';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('stock');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // server side uncounted items processing.
    public function fetch_uncounted()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                // form data.
                $DepartmentID = $this->input->post('DepartmentID');
                $CategoryID = $this->input->post('CategoryID');
                $SubCategoryID = $this->input->post('SubCategoryID');
                // dataTable defined details.
                $start = $this->input->post('start', true);
                $length = $this->input->post('length', true);
                $search = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $records = $this->Stocktake->uncountedcount();
                $totalFiltered = $this->Stocktake->filtereduncounted($search, $DepartmentID, $CategoryID, $SubCategoryID);
                $entries = $this->Stocktake->uncounted($search, $order_by, $order_dir, $start, $length, $DepartmentID, $CategoryID, $SubCategoryID);
                // prepare response.
                $data = [];
                foreach ($entries as $row) {
                    $data[] = [
                        $row->Code,
                        $row->Alias,
                        $row->description,
                        $row->Cost,
                        $row->Price,
                        $row->OriginalQty,
                        $row->OriginalQty * $row->Cost,
                        $row->OriginalQty * $row->Price
                    ];
                }
                //return json response
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => intval($this->input->post('draw')),
                    "recordsTotal" => $records,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
                break;
            default:
                redirect('welcome');
        }
    }

    // un-counted items during stock take.
    public function uncounted()
    {
        if ($this->session->userdata('logged')) {
            $data['title'] = 'Uncounted SKUs';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            // $data['products'] = $this->Stocktake->uncounted();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('uncounted');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // server side processing of shests count.
    public function fetch_sheets()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                // dataTable defined details.
                $start = $this->input->post('start', true);
                $length = $this->input->post('length', true);
                $search = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $records = $this->Stocktake->binscount();
                $totalFiltered = $this->Stocktake->filteredbins($search);
                $entries = $this->Stocktake->binsheets($search, $order_by, $order_dir, $start, $length);
                // prepare response.
                $data = [];
                foreach ($entries as $row) {
                    $data[] = [
                        $row->fedtime,
                        $row->bin,
                        $row->itemcode,
                        $row->Description,
                        $row->Cost,
                        $row->Price,
                        $row->Quantity,
                        $row->Quantity * $row->Cost,
                        $row->Quantity * $row->Price,
                        $row->username
                    ];
                }
                //return json response
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => intval($this->input->post('draw')),
                    "recordsTotal" => $records,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
                break;
            default:
                redirect('welcome');
        }
    }

    // Bin/(Shelf) Counts.
    public function binsheets()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Bin Sheets';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            //$data['sheets'] = $this->Stocktake->binsheets();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('shelves');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Getting Category's department.
    public function get_categories_department()
    {
        $id = $this->input->post('departmentid');

        $data['Category'] = $this->Stocktake->get_categories_department($id);
        echo json_encode($data);
    }

    // Selecting subcategories in the specified department method.
    public function get_subcategories_department()
    {
        $id = $this->input->post('categoryid');

        $data['SubCategory'] = $this->Stocktake->get_subcategories_department($id);
        echo json_encode($data);
    }

    // Showing stocktake process method.
    public function fsheets()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Feed Sheets';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['tsheets'] = $this->Stocktake->tempsheets();
            $data['shelf'] = $this->Stocktake->shelf();
            $data['reason'] = $this->Stocktake->reasoncode();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('stocktake');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // server side processing of sheets, method.
    public function fetch_entries()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $LookupCode = $this->input->post('LookupCode');
                // dataTable defined details.
                $start = $this->input->post('start', true);
                $length = $this->input->post('length', true);
                $search = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $records = $this->Stocktake->sheetscount();
                $totalFiltered = $this->Stocktake->filteredsheets($search, $LookupCode);
                $entries = $this->Stocktake->syncstocksheets($search, $order_by, $order_dir, $start, $length, $LookupCode);
                // prepare response.
                $data = [];
                foreach ($entries as $row) {
                    $data[] = [
                        $row->ID,
                        $row->bin,
                        $row->ItemCode,
                        $row->Alias,
                        $row->Description,
                        $row->Cost,
                        $row->Price,
                        $row->Quantity,
                        $row->costValue,
                        $row->priceValue,
                        $row->Username
                    ];
                }
                //return json response
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => intval($this->input->post('draw')),
                    "recordsTotal" => $records,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
                break;
            default:
                redirect('welcome');
        }
    }

    // Synching the sheets that have been fed method.
    public function syncstocksheets()
    {
        if ($this->session->userdata('logged')) {
            // Header title
            $data['title'] = 'Synchronise sheets';
            $data['config'] = $this->Stocktake->storeconfig();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['stocktakesheets'] = $this->Stocktake->stocktakesheets();
            $data['tempsheets_status'] = $this->Stocktake->tempsheets_status();
            $data['pendingsyncrecords'] = $this->Stocktake->pendingsyncrecords();
            $data['pendings'] = $this->Stocktake->pending_tempsheets();
            $data['shelf'] = $this->Stocktake->shelf();
            //$data['psynchs'] = $this->Stocktake->syncstocksheets();
            $data['total'] = $this->Stocktake->syncstocksheetsval();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('syncstocks');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Searching specific item feed.
    public function specific_feed()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Specific Feed';
            $LookupCode = $this->input->post('LookupCode');
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['stocktakesheets'] = $this->Stocktake->stocktakesheets();
            $data['total'] = $this->Stocktake->syncstocksheetsval();
            $data['psynchs'] = $this->Stocktake->specific_feed($LookupCode);
            $data['shelf'] = $this->Stocktake->shelf();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('syncstocks');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Stock take function.
    public function stock_take()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->stock_take();
                if ($save == 1) {
                    $this->session->set_flashdata('res', 'Item entered successully.');
                } else if ($save == 2) {
                    $this->session->set_flashdata('updating', 'Item updated successully.');
                } else if ($save == 3) {
                    $this->session->set_flashdata('missing', 'Code doesn\'t exist.');
                } else if ($save == 4) {
                    $this->session->set_flashdata('adding', 'Quantity updated successully.');
                } else if ($save == 8) {
                    $this->session->set_flashdata('bin', 'Set the bin');
                } else if ($save == 9) {
                    $this->session->set_flashdata('entries', 'Maximum sheet entries reached, please save the sheet to continue');
                } else {
                    $this->session->set_flashdata('sheetchange', 'Please save the current sheet to continue.');
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // un-doing initiated stock take function.
    public function undofreeze()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->undofreeze();
                if ($save == 1) {
                    echo "Stocks unfrozen successully.";
                    redirect("fstocks", "refresh");
                } else {
                    echo "Error  unfreezing.";
                    redirect("fstocks", "refresh");
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // updating tempsheets before synching.
    public function updatedetail()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->updatedetail();
                if ($save == 1) {
                    $this->session->set_flashdata('res', 'Item updated successully.');
                } else if ($save == 2) {
                    $this->session->set_flashdata('updating', 'Error updating.');
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // removing an entry in tempsheet.
    public function remove($id)
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->deletebinentry($id);
                if ($save == 1) {
                    $this->session->set_flashdata('res', 'Entry deleted successully.');
                    redirect("fsheets", "refresh");
                } else if ($save == 2) {
                    $this->session->set_flashdata('updating', 'Error deleting.');
                    redirect("fsheets", "refresh");
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // deleting entry in the stock sheet table.
    public function del_sheet_entry($id)
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->del_sheet_entry($id);
                if ($save == 1) {
                    $this->session->set_flashdata('res', 'Entry deleted successully.');
                    redirect("syncstocksheets", "refresh");
                } else if ($save == 2) {
                    $this->session->set_flashdata('updating', 'Error deleting.');
                    redirect("syncstocksheets", "refresh");
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // Synchronise stock take information.
    public function sync_stocks()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->sync_stocks();
                // Instead of flashdata, return JSON response for AJAX
                echo json_encode([
                    'status' => $save ? 'success' : 'error',
                    'message' => $save ? 'Data synchronized successfully!' : 'Sync failed. Please try again.'
                ]);
                exit;
                break;
            default:
                redirect('welcome');
        }
    }

    // posting stock sheet after the user confirms */
    public function post_sheets()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->post_sheets();
                if ($save == 1) {
                    echo "Sheet posted successully.";
                    // $this->session->set_flashdata('sheet', 'Sheet posted successully.');
                    redirect("fsheets", "refresh");
                } else {
                    echo "Error Posting.";
                    // $this->session->set_flashdata('sheet', 'Error Posting.');
                    redirect("fsheets", "refresh");
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // Stock take entry details.
    public function product()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $id = $this->input->post('ItemID');
                $data['product'] = $this->Stocktake->product($id);

                echo json_encode($data);
                break;
            default:
                redirect('welcome');
        }
    }

    // Getting searched item code.
    public function code_desc()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $id = $this->input->GET('code');
                $data = $this->Stocktake->code_desc($id);

                echo json_encode($data);
                break;
            default:
                redirect('welcome');
        }
    }

    // Updating the existing SKU with the found quantities.
    public function updatecode()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->updatecode();
                if ($save == 6) {
                    $this->session->set_flashdata('added', 'Quantity updated successully.');
                    redirect("fsheets", "refresh");
                } else {
                    $this->session->set_flashdata('missing', 'Code operation error.');
                    redirect("fsheets", "refresh");
                }
                break;
            default:
                redirect('welcome');
        }
    }

    public function cancelcode()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->cancelcode();
                if ($save == 4) {
                    $this->session->set_flashdata('res', 'Operation aborted.');
                    redirect("fsheets", "refresh");
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // Historical stock takes.
    public function history()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'History';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['tsheets'] = $this->Stocktake->tempsheets();
            $data['users'] = $this->Stocktake->users();
            $data['history'] = $this->Stocktake->history();
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('history');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // server side processing.
    public function fetch_details($id)
    {
        switch ($this->session->userdata('logged')) {
            case true:
                // dataTable defined details.
                $start = $this->input->post('start', true);
                $length = $this->input->post('length', true);
                $search = $this->input->post('search')['value'];
                $order_column = $this->input->post('order')[0]['column'];
                $order_dir = $this->input->post('order')[0]['dir'];

                $records = $this->Stocktake->details_count($id);
                $totalFiltered = $this->Stocktake->filtered_details($search, $id);
                $entries = $this->Stocktake->details($search, $order_by, $order_dir, $start, $length, $id);
                // prepare response.
                $data = [];
                foreach ($entries as $row) {
                    $data[] = [
                        $row->department,
                        $row->lookup,
                        $row->Alias,
                        $row->Description,
                        $row->Cost,
                        $row->OriginalQty,
                        $row->Cost * $row->OriginalQty,
                        $row->CountedQty,
                        $row->Cost * $row->CountedQty
                    ];
                }
                //return json response
                header('Content-Type: application/json');
                echo json_encode([
                    "draw" => intval($this->input->post('draw')),
                    "recordsTotal" => $records,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
                break;
            default:
                redirect('welcome');
        }
    }

    // Stock take history details
    public function stocktakedetails($id)
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Stock History';
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['departments'] = $this->Stocktake->departments();
            // $data['details'] = $this->Stocktake->details($id);
            $data['info'] = $this->Stocktake->get_record($id);
            $data['config'] = $this->Stocktake->storeconfig();

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('countdetails');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // Searching historical stock takes details.
    public function historysearch()
    {
        if ($this->session->userdata('logged')) {
            // Header title.
            $data['title'] = 'Current Stocks';
            $user = $this->input->post('userid');
            $startdate = date('Y-m-d', strtotime($this->input->post('sDate')));
            $enddate = date('Y-m-d', strtotime($this->input->post('eDate')));

            // Case 1: Where a user is quering specifics(Department,category & subcategory).
            $data['config'] = $this->Stocktake->storeconfig();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            $data['users'] = $this->Stocktake->users();
            $data['freeze'] = $this->Stocktake->historysearch($user, $startdate, $enddate);

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('history');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    // updating password.
    public function update_password()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $save = $this->Stocktake->updatepassword();
                if ($save == 0) {
                    $data["miss"] = "The Email Address doesn't Exist.";
                    $this->load->view('common/header', $data);
                } else {
                    echo "Password updated successully";
                }
                break;
            default:
                redirect('welcome');
        }
    }

    // Importing  Excel files(:bin sheets).
    public function importData()
    {
        if ($this->input->post('submit')) {
            $path = 'assets/uploads/';
            require_once APPPATH . "/third_party/PHPExcel.php";
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls|csv';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('uploadFile')) {
                die("Upload error: " . $this->upload->display_errors());
            } else {
                $data = $this->upload->data();
                echo "File uploaded: " . $data['file_name'];
            }
            if (!empty($data['file_name'])) {
                $inputFileName = $path . $data['file_name'];
            } else {
                die("No file uploaded.");
            }
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                // Debug: Print Excel Data
                echo "<pre>";
                print_r($allDataInSheet);
                echo "</pre>";
                exit;
                $flag = true;
                $inserdata = [];
                foreach ($allDataInSheet as $value) {
                    if ($flag) {
                        $flag = false;
                        continue;
                    }
                    $inserdata[] = [
                        'Shelf' => $value['A'],
                        'ItemLookupCode' => $value['B'],
                        'Description' => $value['C'],
                    ];
                }
                // Debug: Print Data Before Insert
                echo "<pre>";
                print_r($inserdata);
                echo "</pre>";
                exit;
                $result = $this->Stocktake->importData($inserdata);
                if ($result) {
                    echo "Imported successfully";
                    redirect("import_sheets", "refresh");
                } else {
                    die("Database Insert Failed.");
                }
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }
        }
        redirect("import_sheets", "refresh");
    }

    // excels methods.
    public function excel()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $stocks = $this->Stocktake->holdings_excel();
                if (empty($stocks)) {
                    exit('No data available to export.');
                }
                // Increase memory and execution time limits
                ini_set('memory_limit', '2G');
                ini_set('max_execution_time', 300);
                // Enable memory-based caching (prevents high memory usage)
                Settings::setCache(new Memory());

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                // Set headers dynamically
                $headers = ['StockTake Date', 'Code', 'Alias', 'Description', 'Cost', 'Price', 'Counted', 'Counted Value', 'Available', 'Available Value'];
                $column = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($column . '1', $header);
                    $column++;
                }
                // stream data row by row (avoiding large memory usage)
                $row = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValueExplicit('A' . $row, $val->CountingDate, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('B' . $row, $val->itemcode, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('C' . $row, $val->Alias, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $row, $val->Description, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $row, $val->Cost, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('F' . $row, $val->Price, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('G' . $row, $val->CountedQty, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('H' . $row, $val->countedValue, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('I' . $row, $val->OriginalQty, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('J' . $row, $val->availableValue, DataType::TYPE_NUMERIC);
                    $row++;
                    // Flush memory every 10,000 rows
                    if ($row % 10000 === 0) {
                        gc_collect_cycles();
                    }
                }
                // Write to output
                $writer = new Xlsx($spreadsheet);
                $fileName = 'Current_Holding.xlsx';
                ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Synch sheets method.
    public function synchronize()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $stocks = $this->Stocktake->syncstocksheets_excel();
                if (empty($stocks)) {
                    exit('No data available to export.');
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Bin/(Shelf):');
                $sheet->setCellValue('B1', 'Code');
                $sheet->setCellValue('C1', 'Scan Code');
                $sheet->setCellValue('D1', 'Description');
                $sheet->setCellValue('E1', 'Quantity');
                $sheet->setCellValue('F1', 'Cost');
                $sheet->setCellValue('G1', 'Price');
                $sheet->setCellValue('H1', 'Total Cost');
                $sheet->setCellValue('I1', 'Total Price');
                $sheet->setCellValue('J1', 'User');
                $rows = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValue('A' . $rows, $val->bin);
                    $sheet->setCellValue('B' . $rows, $val->ItemCode);
                    $sheet->setCellValue('C' . $rows, $val->Alias);
                    $sheet->setCellValue('D' . $rows, $val->Description);
                    $sheet->setCellValue('E' . $rows, $val->Quantity);
                    $sheet->setCellValue('F' . $rows, $val->Cost);
                    $sheet->setCellValue('G' . $rows, $val->Price);
                    $sheet->setCellValue('H' . $rows, $val->Quantity * $val->Cost);
                    $sheet->setCellValue('I' . $rows, $val->Quantity * $val->Price);
                    $sheet->setCellValue('J' . $rows, $val->Username);
                    $rows++;
                }
                $writer = new Xlsx($spreadsheet); // instantiate Xlsx
                $fileName = 'Stock Take Progress'; // set filename for excel file to be exported
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel'); // generate excel file
                header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');    // download file
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Counted items in excel
    public function countedexcel()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $stocks = $this->Stocktake->stocks_excel();
                if (empty($stocks)) {
                    exit('No data available to export.');
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'Date');
                $sheet->setCellValue('B1', 'Code');
                $sheet->setCellValue('C1', 'Description');
                $sheet->setCellValue('D1', 'Cost');
                $sheet->setCellValue('E1', 'Available Qty');
                $sheet->setCellValue('F1', 'Available Value');
                $sheet->setCellValue('G1', 'Counted Qty');
                $sheet->setCellValue('H1', 'Counted Value');
                $rows = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValue('A' . $rows, $val->CountedDate);
                    $sheet->setCellValue('B' . $rows, $val->Code);
                    $sheet->setCellValue('C' . $rows, $val->description);
                    $sheet->setCellValue('D' . $rows, $val->Cost);
                    $sheet->setCellValue('E' . $rows, $val->OriginalQty);
                    $sheet->setCellValue('F' . $rows, $val->OriginalQty * $val->Cost);
                    $sheet->setCellValue('G' . $rows, $val->CountedQty);
                    $sheet->setCellValue('H' . $rows, $val->CountedQty * $val->Cost);
                    $rows++;
                }
                $writer = new Xlsx($spreadsheet); // instantiate Xlsx
                $fileName = 'counted_skus.xlsx'; // set filename for excel file to be exported
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel'); // generate excel file
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');    // download file
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Counted items in excel
    public function uncounted_excel()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $stocks = $this->Stocktake->uncounted_excel();
                if (empty($stocks)) {
                    exit('No data available to export.');
                }

                // Increase memory and execution time limits
                ini_set('memory_limit', '2G');
                ini_set('max_execution_time', 300);

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Set headers dynamically
                $headers = ['StockTake Date', 'Code', 'Alias', 'Description', 'SupplierName', 'Department', 'Category', 'Subcategory',
                    'Cost', 'Price', 'Quantity', 'Total Cost', 'Total Price'];
                $column = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($column . '1', $header);
                    $column++;
                }
                // Stream data row by row (avoiding large memory usage)
                $rows = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValueExplicit('A' . $rows, $val->CountedDate, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('B' . $rows, $val->ItemLookupCode, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('C' . $rows, $val->Alias, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $rows, $val->description, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $rows, $val->SupplierName, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $rows, $val->department, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('G' . $rows, $val->category, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('H' . $rows, $val->subcategory, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('I' . $rows, $val->Cost, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('J' . $rows, $val->Price, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('K' . $rows, $val->OriginalQty, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('L' . $rows, $val->availableValue, DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('M' . $rows, $val->priceValue, DataType::TYPE_NUMERIC);
                    //$sheet->setCellValueExplicit('J' . $rows, $val->countedValue, DataType::TYPE_NUMERIC);
                    $rows++;

                    // Flush memory every 10,000 rows
                    if ($rows % 10000 === 0) {
                        gc_collect_cycles();
                    }
                }

                // Write to output
                $writer = new Xlsx($spreadsheet);
                $fileName = 'un_counted_skus.xlsx';
                ob_end_clean();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Bin counts report(:shelf).
    public function binexcel()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $stocks = $this->Stocktake->binsheets_excel();
                if (empty($stocks)) {
                    exit('No data available to export.');
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Stock Date');
                $sheet->setCellValue('B1', 'Bin/(Shelf):');
                $sheet->setCellValue('C1', 'Code');
                $sheet->setCellValue('D1', 'Description');
                $sheet->setCellValue('E1', 'Cost');
                $sheet->setCellValue('F1', 'Price');
                $sheet->setCellValue('G1', 'Counted Qty');
                $sheet->setCellValue('H1', 'Total Cost');
                $sheet->setCellValue('I1', 'Total Price');
                $sheet->setCellValue('J1', 'User');
                $rows = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValue('A' . $rows, $val->fedtime);
                    $sheet->setCellValue('B' . $rows, $val->bin);
                    $sheet->setCellValue('C' . $rows, $val->itemcode);
                    $sheet->setCellValue('D' . $rows, $val->Description);
                    $sheet->setCellValue('E' . $rows, $val->Cost);
                    $sheet->setCellValue('F' . $rows, $val->Price);
                    $sheet->setCellValue('G' . $rows, $val->Quantity);
                    $sheet->setCellValue('H' . $rows, $val->Quantity * $val->Cost);
                    $sheet->setCellValue('I' . $rows, $val->Quantity * $val->Price);
                    $sheet->setCellValue('J' . $rows, $val->username);
                    $rows++;
                }
                $writer = new Xlsx($spreadsheet); // instantiate Xlsx
                $fileName = 'Bin(shelf) Report'; // set filename for excel file to be exported
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel'); // generate excel file
                header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');    // download file
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Historical stock take excel.
    public function detailsexcel($id)
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $stocks = $this->Stocktake->excel_details($id);
                if (empty($stocks)) {
                    exit('No data available to export.');
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Department');
                $sheet->setCellValue('B1', 'Code');
                $sheet->setCellValue('C1', 'Alias');
                $sheet->setCellValue('D1', 'Description');
                $sheet->setCellValue('E1', 'Cost');
                $sheet->setCellValue('F1', 'Available Qty');
                $sheet->setCellValue('G1', 'Available Value');
                $sheet->setCellValue('H1', 'Counted Qty');
                $sheet->setCellValue('I1', 'Counted Value');
                $rows = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValue('A' . $rows, $val->department);
                    $sheet->setCellValue('B' . $rows, $val->lookup);
                    $sheet->setCellValue('c' . $rows, $val->Alias);
                    $sheet->setCellValue('D' . $rows, $val->Description);
                    $sheet->setCellValue('E' . $rows, $val->Cost);
                    $sheet->setCellValue('F' . $rows, $val->OriginalQty);
                    $sheet->setCellValue('G' . $rows, ($val->OriginalQty * $val->Cost));
                    $sheet->setCellValue('H' . $rows, $val->CountedQty);
                    $sheet->setCellValue('I' . $rows, $val->CountedQty * $val->Cost);
                    $rows++;
                }

                $writer = new Xlsx($spreadsheet); // instantiate Xlsx
                $fileName = 'Stocktake"' . $id . '"History'; // set filename for excel file to be exported
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel'); // generate excel file
                header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');    // download file
                redirect(base_url('stocktakedetails/' . $id));
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Summary History List Excel.
    public function historyexcel()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $summary = $this->Stocktake->history();
                if (empty($summary)) {
                    exit('No data available to export.');
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Branch');
                $sheet->setCellValue('B1', 'Start Date');
                $sheet->setCellValue('C1', 'Initiated By');
                $sheet->setCellValue('D1', 'Commited By');
                $sheet->setCellValue('E1', 'Closed By');
                $rows = 2;
                foreach ($summary as $val) {
                    $sheet->setCellValue('A' . $rows, $val->Description);
                    $sheet->setCellValue('B' . $rows, $val->CountingDate);
                    $sheet->setCellValue('C' . $rows, $val->InitiatedByName);
                    $sheet->setCellValue('D' . $rows, $val->CommittedName);
                    $sheet->setCellValue('E' . $rows, $val->DateCommitted);
                    $rows++;
                }
                $writer = new Xlsx($spreadsheet); // instantiate Xlsx
                $fileName = 'Stocktake History summary'; // set filename for excel file to be exported
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel'); // generate excel file
                header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');    // download file
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Custom search Excel.
    public function customexcel()
    {
        switch ($this->session->userdata('logged')) {
            case true:
                $DepartmentID = $this->input->post('DepartmentID');
                $CategoryID = $this->input->post('CategoryID');
                $SubCategoryID = $this->input->post('SubCategoryID');

                $stocks = $this->Stocktake->customised_holdings($DepartmentID, $CategoryID, $SubCategoryID);
                if (empty($stocks)) {
                    exit('No data available to export.');
                }
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Stock Date');
                $sheet->setCellValue('B1', 'Code');
                $sheet->setCellValue('C1', 'Description');
                $sheet->setCellValue('D1', 'Cost');
                $sheet->setCellValue('E1', 'Price');
                $sheet->setCellValue('F1', 'Available Qty');
                $sheet->setCellValue('G1', 'Available Value');
                $sheet->setCellValue('H1', 'Counted Qty');
                $sheet->setCellValue('I1', 'Counted Value');
                $rows = 2;
                foreach ($stocks as $val) {
                    $sheet->setCellValue('A' . $rows, $val->CountingDate);
                    $sheet->setCellValue('B' . $rows, $val->itemcode);
                    $sheet->setCellValue('C' . $rows, $val->Description);
                    $sheet->setCellValue('D' . $rows, $val->Cost);
                    $sheet->setCellValue('E' . $rows, $val->Price);
                    $sheet->setCellValue('F' . $rows, $val->OriginalQty);
                    $sheet->setCellValue('G' . $rows, $val->OriginalQty * $val->Cost);
                    $sheet->setCellValue('H' . $rows, $val->CountedQty);
                    $sheet->setCellValue('I' . $rows, $val->CountedQty * $val->Cost);
                    $rows++;
                }
                $writer = new Xlsx($spreadsheet); // instantiate Xlsx
                $fileName = '" ' . $DepartmentID . ' " Stocktake.xlsx'; // set filename for excel file to be exported
                ob_end_clean();
                header('Content-Type: application/vnd.ms-excel'); // generate excel file
                header('Content-Disposition: attachment;filename="' . $fileName . '"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');    // download file
                exit();
                break;
            default:
                redirect('welcome');
        }
    }

    // Sign out method.
    public function logout()
    {
        $details = array('ID', 'LastUpdated', 'Number', 'StoreID', 'Name', 'FloorLimit', 'ReturnLimit', 'DropLimit', 'CashDrawerNumber', 'SecurityLevel', 'Priviledges', 'EmailAddress', 'FailedLogonAttempts', 'MaxOverShortAmount', 'OverShortLimitType', 'Telephone', 'Enabled', 'TimeSchedule', 'LastPasswordChange', 'PassExpires', 'InventoryLocation', 'SalesRepID', 'BinLocation', 'logged');

        $this->session->unset_userdata($details);
        $this->session->sess_destroy();
        redirect('welcome');
    }
}
