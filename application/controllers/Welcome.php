<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . '/vendor/autoload.php';

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
        error_reporting(0);
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

    /**Dashboard method */
    public function dashboard()
    {
        if ($this->session->userdata('logged')) {
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();

            $data['progress'] = $this->Stocktake->sheetprogress();
            //$data['department'] = $this->Stocktake->departmentalprogress();
            $data['title'] = 'Dashboard';

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

            $data['users'] = $this->Stocktake->system_users();
            $data['userlevels'] = $this->Stocktake->userlevels();
            $data['branches'] = $this->Stocktake->branches();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Users';

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('users');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** Adding a new user method*/
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
            /** departments,categories & subcategories */
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            $data['items'] = $this->Stocktake->items();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Items';

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
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->departments();
            $data['title'] = 'Departments';

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

            $data['suppliers'] = $this->Stocktake->suppliers();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Suppliers';

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('suppliers');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** List of all the customers */
    public function customers()
    {
        if ($this->session->userdata('logged')) {

            $data['customers'] = $this->Stocktake->customers();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Customers';

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
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['products'] = $this->Stocktake->transactions();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Stock Take Sheets';

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
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->psheets();
            /** Header title */
            $data['title'] = 'Print Sheet';

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
            $data['config'] = $this->Stocktake->storeconfig();
            $data['freeze'] = $this->Stocktake->stocktakestatus();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['stocktakeprogress'] = $this->Stocktake->stocktakeprogress();
            /** departments,categories & subcategories */
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            /** Header title */
            $data['title'] = 'Stocks Freeze';

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('stockfreeze');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** Posting stock take page method */
    public function stocksposting()
    {
        if ($this->session->userdata('logged')) {
            $data['config'] = $this->Stocktake->storeconfig();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['pendingsyncrecords'] = $this->Stocktake->pendingsyncrecords();
            $data['pendings'] = $this->Stocktake->pending_tempsheets();
            $data['tempsheets_status'] = $this->Stocktake->tempsheets_status();
            /** Header title */
            $data['title'] = 'Post Stocks';

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

    /** Posting all counted SKUs and reseting uncounted to Zero */
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

    /** Posting only counted SKUs only */
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

    /** Freezing stocks for a stock take */
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

    /** Bin numbers import interface  */
    public function import_sheets()
    {
        if ($this->session->userdata('logged')) {
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Import Stock Sheets';

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
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            // Header title
            $data['title'] = 'Stock Take';

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

    // Uncounted items during stock take */
    public function uncounted()
    {
        if ($this->session->userdata('logged')) {
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['config'] = $this->Stocktake->storeconfig();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            // $data['products'] = $this->Stocktake->uncounted();

            $data['title'] = 'Uncounted SKUs';


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

    /** Bin/(Shelf) Counts */
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

    /** Getting Category's department */
    public function get_categories_department()
    {
        $id = $this->input->post('departmentid');

        $data['Category'] = $this->Stocktake->get_categories_department($id);
        echo json_encode($data);
    }

    /** Selecting subcategories in the specified department method */
    public function get_subcategories_department()
    {
        $id = $this->input->post('categoryid');

        $data['SubCategory'] = $this->Stocktake->get_subcategories_department($id);
        echo json_encode($data);
    }

    /** Showing stocktake process method */
    public function fsheets()
    {
        if ($this->session->userdata('logged')) {
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['tsheets'] = $this->Stocktake->tempsheets();
            $data['shelf'] = $this->Stocktake->shelf();
            $data['reason'] = $this->Stocktake->reasoncode();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Feed Sheets';

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
                        $row->Cost * $row->Quantity,
                        $row->Price * $row->Quantity
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

    /** Synching the sheets that have been fed method */
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

    /** Searching specific item feed */
    public function specific_feed()
    {
        if ($this->session->userdata('logged')) {
            $LookupCode = $this->input->post('LookupCode');

            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['stocktakesheets'] = $this->Stocktake->stocktakesheets();
            $data['total'] = $this->Stocktake->syncstocksheetsval();
            $data['psynchs'] = $this->Stocktake->specific_feed($LookupCode);
            $data['shelf'] = $this->Stocktake->shelf();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Specific Feed';

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('syncstocks');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** Stock take function */
    public function stock_take()
    {
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
    }

    /** Un-doing initiated stock take function */
    public function undofreeze()
    {
        $save = $this->Stocktake->undofreeze();

        if ($save == 1) {
            echo "Stocks unfrozen successully.";

            redirect("fstocks", "refresh");
        } else {
            echo "Error  unfreezing.";

            redirect("fstocks", "refresh");
        }
    }

    /** Updating tempsheets before synching */
    public function updatedetail()
    {
        $save = $this->Stocktake->updatedetail();

        if ($save == 1) {
            $this->session->set_flashdata('res', 'Item updated successully.');
        } else if ($save == 2) {
            $this->session->set_flashdata('updating', 'Error updating.');
        }
    }

    /** Removing an entry in tempsheet */
    public function remove($id)
    {
        $save = $this->Stocktake->deletebinentry($id);

        if ($save == 1) {
            $this->session->set_flashdata('res', 'Entry deleted successully.');

            redirect("fsheets", "refresh");
        } else if ($save == 2) {
            $this->session->set_flashdata('updating', 'Error deleting.');

            redirect("fsheets", "refresh");
        }
    }

    /** Deleting entry in the stock sheet table */
    public function del_sheet_entry($id)
    {
        $save = $this->Stocktake->del_sheet_entry($id);

        if ($save == 1) {
            $this->session->set_flashdata('res', 'Entry deleted successully.');

            redirect("syncstocksheets", "refresh");
        } else if ($save == 2) {
            $this->session->set_flashdata('updating', 'Error deleting.');

            redirect("syncstocksheets", "refresh");
        }
    }

    /** Synchronise stock take information */
    public function sync_stocks()
    {
        $save = $this->Stocktake->sync_stocks();

        if ($save == 1) {
            echo "Synched successully.";

            redirect("syncstocksheets", "refresh");
        } else {
            echo "Error synching.";

            redirect("syncstocksheets", "refresh");
        }
    }

    /** Posting stock sheet after the user confirms */
    public function post_sheets()
    {
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
    }

    /** Stock take entry details */
    public function product()
    {

        $id = $this->input->post('ItemID');
        $data['product'] = $this->Stocktake->product($id);

        echo json_encode($data);
    }

    /** Getting searched item code */
    public function code_desc()
    {
        $id = $this->input->GET('code');
        $data = $this->Stocktake->code_desc($id);

        echo json_encode($data);
    }

    /** Updating the existing SKU with the found quantities */
    public function updatecode()
    {
        $save = $this->Stocktake->updatecode();

        if ($save == 6) {
            $this->session->set_flashdata('added', 'Quantity updated successully.');
            redirect("fsheets", "refresh");
        } else {
            $this->session->set_flashdata('missing', 'Code operation error.');
            redirect("fsheets", "refresh");
        }
    }

    public function cancelcode()
    {
        $save = $this->Stocktake->cancelcode();

        if ($save == 4) {
            $this->session->set_flashdata('res', 'Operation aborted.');
            redirect("fsheets", "refresh");
        }
    }

    /** Historical stock takes */
    public function history()
    {
        if ($this->session->userdata('logged')) {
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['tsheets'] = $this->Stocktake->tempsheets();
            $data['users'] = $this->Stocktake->users();
            $data['history'] = $this->Stocktake->history();
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'History';

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
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['departments'] = $this->Stocktake->departments();
            //$data['details'] = $this->Stocktake->details($id);
            $data['info'] = $this->Stocktake->get_record($id);
            $data['config'] = $this->Stocktake->storeconfig();
            /** Header title */
            $data['title'] = 'Stock History';

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('countdetails');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** Searching historical stock takes details */
    public function historysearch()
    {
        if ($this->session->userdata('logged')) {

            $user = $this->input->post('userid');
            $startdate = date('Y-m-d', strtotime($this->input->post('sDate')));
            $enddate = date('Y-m-d', strtotime($this->input->post('eDate')));

            /** Case 1: Where a user is quering specifics(Department,category & subcategory) */
            $data['config'] = $this->Stocktake->storeconfig();
            $data['stocktakestatus'] = $this->Stocktake->stocktakestatus();
            $data['departments'] = $this->Stocktake->departments();
            $data['categories'] = $this->Stocktake->category();
            $data['subcategories'] = $this->Stocktake->subcategory();
            $data['users'] = $this->Stocktake->users();
            $data['freeze'] = $this->Stocktake->historysearch($user, $startdate, $enddate);
            /** Header title */
            $data['title'] = 'Current Stocks';

            $this->load->view('common/header', $data);
            $this->load->view('common/menu');
            $this->load->view('history');
            $this->load->view('common/footer');
        } else {
            redirect('welcome');
        }
    }

    /** Updating password */
    public function update_password()
    {
        $save = $this->Stocktake->updatepassword();

        if ($save == 0) {
            $data["miss"] = "The Email Address doesn't Exist.";
            $this->load->view('common/header', $data);
        } else {
            echo "Password updated successully";
        }
    }

    /** Importing  Excel files(:bin sheets) */
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
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
            if (empty($error)) {
                if (!empty($data['upload_data']['file_name'])) {
                    $import_xls_file = $data['upload_data']['file_name'];
                } else {
                    $import_xls_file = 0;
                }
                $inputFileName = $path . $import_xls_file;

                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    $flag = true;
                    $i = 0;
                    foreach ($allDataInSheet as $value) {
                        if ($flag) {
                            $flag = false;
                            continue;
                        }
                        $inserdata[$i]['Shelf'] = $value['A'];
                        $inserdata[$i]['ItemLookupCode'] = $value['B'];
                        $inserdata[$i]['Description'] = $value['C'];
                        $i++;
                    }
                    $result = $this->Stocktake->importData($inserdata);
                    if ($result) {
                        echo "Imported successfully";

                        redirect("import_sheets", "refresh");
                    } else {
                        echo "ERROR !";
                    }
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
                }
            } else {
                echo $error['error'];
                redirect("import_sheets", "refresh");
            }
        }
        redirect("import_sheets", "refresh");
    }

    /** Excels methods */
    public function excel()
    {
        //$fileName = 'sales.xlsx';
        $stocks = $this->Stocktake->holdings();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Stock ');
        $sheet->setCellValue('B1', 'Code');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Cost');
        $sheet->setCellValue('E1', 'Price');
        $sheet->setCellValue('F1', 'Counted');
        $sheet->setCellValue('G1', 'Counted Value');
        $sheet->setCellValue('H1', 'Available');
        $sheet->setCellValue('I1', 'Available Value');

        $rows = 2;
        //var_dump($sales);

        foreach ($stocks as $val) {

            $sheet->setCellValue('A' . $rows, $val->CountingDate);
            $sheet->setCellValue('B' . $rows, $val->itemcode);
            $sheet->setCellValue('C' . $rows, $val->Description);
            $sheet->setCellValue('D' . $rows, $val->Cost);
            $sheet->setCellValue('E' . $rows, $val->Price);
            $sheet->setCellValue('F' . $rows, $val->CountedQty);
            $sheet->setCellValue('G' . $rows, $val->CountedQty * $val->Cost);
            $sheet->setCellValue('H' . $rows, $val->OriginalQty);
            $sheet->setCellValue('I' . $rows, $val->OriginalQty * $val->Cost);

            $rows++;
        }

        $writer = new Xlsx($spreadsheet); // instantiate Xlsx

        $fileName = 'Stock Take'; // set filename for excel file to be exported

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /** Synch sheets method */
    public function synchronize()
    {
        //$fileName = 'sales.xlsx';
        $stocks = $this->Stocktake->syncstocksheets();

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
        //var_dump($sales);

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

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /** Counted items in excel**/
    public function countedexcel()
    {
        //$fileName = 'sales.xlsx';
        $stocks = $this->Stocktake->stocks();

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
        //var_dump($sales);

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

        $fileName = 'Counted SKUs'; // set filename for excel file to be exported

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /**Bin counts report(:shelf)**/
    public function binexcel()
    {
        //$fileName = 'sales.xlsx';
        $stocks = $this->Stocktake->binsheets();

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
        //var_dump($sales);

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

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /** Historical stock take excel */
    public function detailsexcel($id)
    {
        //$fileName = 'sales.xlsx';
        $stocks = $this->Stocktake->details($id);

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
        //var_dump($sales);

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

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /**Summary History List Excel */
    public function historyexcel()
    {
        $summary = $this->Stocktake->history();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Branch');
        $sheet->setCellValue('B1', 'Start Date');
        $sheet->setCellValue('C1', 'Initiated By');
        $sheet->setCellValue('D1', 'Commited By');
        $sheet->setCellValue('E1', 'Closed By');

        $rows = 2;
        //var_dump($sales);

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

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /**Custom search Excel */
    public function customexcel()
    {
        $DepartmentID = $this->input->post('DepartmentID');
        $CategoryID = $this->input->post('CategoryID');
        $SubCategoryID = $this->input->post('SubCategoryID');

        $stocks = $this->Stocktake->customised_holdings($DepartmentID, $CategoryID, $SubCategoryID);

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
        //var_dump($sales);

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

        $fileName = '" ' . $DepartmentID . ' " Stocktake'; // set filename for excel file to be exported

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');    // download file
    }

    /** Sign out method  */
    public function logout()
    {
        $details = array('ID', 'LastUpdated', 'Number', 'StoreID', 'Name', 'FloorLimit', 'ReturnLimit', 'DropLimit', 'CashDrawerNumber', 'SecurityLevel', 'Priviledges', 'EmailAddress', 'FailedLogonAttempts', 'MaxOverShortAmount', 'OverShortLimitType', 'Telephone', 'Enabled', 'TimeSchedule', 'LastPasswordChange', 'PassExpires', 'InventoryLocation', 'SalesRepID', 'BinLocation', 'logged');

        $this->session->unset_userdata($details);
        $this->session->sess_destroy();

        redirect('welcome');
    }
}
