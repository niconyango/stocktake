<?php

class Stocktake extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /** Login details method */
    public function login($data)
    {
        $query = $this->db->query("SELECT `ID`, `LastUpdated`, `Number`, `StoreID`, `Name`, `Pass`, `FloorLimit`, `ReturnLimit`, `DropLimit`,`CashDrawerNumber`, `SecurityLevel`, `EmailAddress`, `FailedLogonAttempts`,`MaxOverShortAmount`, `MaxOverShortPercent`, `OverShortLimitType`, `Telephone`, `Enabled`,`TimeSchedule`, `LastPasswordChange`, `PassExpires`, `InventoryLocation`, `SalesRepID`, `BinLocation` FROM `cashier` WHERE `Number` = ? AND `Pass` = PASSWORD(?)", [$data['Number'], $data['Pass']]);
        return $query->row() ?: false;
    }

    /** Sheet progress:Dashboard */
    public function sheetprogress()
    {
        $this->db->select('t.`ItemLookupCode`,t.`Itemdescription`,sum(t.`Quantity`) AS Quantity,sum(t.`Quantity`*e.`Cost`) AS Value');
        $this->db->join('`stocktake` s', 's.`ID`=t.`StockTakeID`');
        $this->db->join('`item` e', 'e.`ID`=t.`ItemID`');
        $this->db->where('s.`Status`', 0);
        $this->db->group_by('t.`ItemID`');
        $this->db->order_by('sum(t.`Quantity`)', 'desc');
        $this->db->limit(10);

        $query = $this->db->get('`tempsheets` t');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Departmental progress */
    public function departmentalprogress()
    {
        $this->db->select('d.`Name` AS department,ROUND(SUM(t.`Quantity`*i.`Cost`)) AS value ');

        $this->db->join('`item` i', 'i.`ID`=t.`ItemID`');
        $this->db->join('`department` d', 'd.`ID`=i.`DepartmentID`');
        $this->db->join('`stocktake` s', 's.`ID`=t.`StockTakeID`');
        $this->db->where('s.`Status`', 0);
        $this->db->group_by('d.`ID`');

        //$query = $this->db->get('`tempsheets` t');
        $query = $this->db->get('`stocksheets` t');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Existing sytem users.
    public function system_users()
    {
        $this->db->select('c.`ID`,c.`StoreID`,c.`Number`,l.`Description`,c.`Pass`,c.`PassExpires`,c.`Name`,c.`EmailAddress`,c.`Telephone`,c.`Enabled`,c.`SecurityLevel` AS Security,u.`Name` AS SecurityLevel');
        $this->db->join('`usergroup` u', 'u.`ID`=c.`SecurityLevel`', 'LEFT');
        $this->db->join('`inventorylocation` l', 'l.`ID`=c.`StoreID`', 'LEFT');
        $this->db->order_by('c.`Name`');

        $query = $this->db->get('`cashier` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // User creation method.
    public function user()
    {
        $id = $this->input->post('action');

        $data['`Number`'] = ucwords($this->input->post('Number'));
        $data['`Name`'] = ucwords($this->input->post('Name'));
        $data['`Telephone`'] = $this->input->post('Telephone');
        $data['`Pass`'] = password_hash($this->input->post('Security'), PASSWORD_DEFAULT);
        $data['`EmailAddress`'] = strtolower($this->input->post('EmailAddress'));
        $data['`StoreID`'] = $this->input->post('StoreID');
        $data['`SecurityLevel`'] = $this->input->post('SecurityLevel');
        $data['`PassExpires`'] = $this->input->post('r1');
        $data['`Lastupdated`'] = date('Y-m-d h:i:s');
        $data['`Enabled`'] = $this->input->post('r3');

        if ($id > 0) {
            $this->db->where('ID', $id);

            $query = $this->db->update('`cashier`', $data);
            // $this->update_edate($id, $data['SupplierID']);

            return 2;
        } else {
            $query = $this->db->insert('`cashier`', $data);

            $id = $this->db->insert_id();
            // $this->update_regdate($id, $data['SupplierID']);

            return 1;
        }
    }

    // Loading user levels.
    public function userlevels()
    {
        $this->db->select('c.`ID`,c.`Name`');

        $query = $this->db->get('`usergroup` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Loding the Branches.
    public function branches()
    {
        $this->db->select('l.`ID`,l.`Description`');

        $query = $this->db->get('`inventorylocation` l');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Items listings.
    public function items()
    {
        $this->db->select('i.`ItemLookupCode` as Code,d.`Name` AS Department,c.`Name` AS Category,s.`Name` AS SubCategory,a.`Alias`,i.`Description`,i.`Cost`,i.`quantity`,i.`Price`');
        $this->db->join("department d", "d.`ID`=i.`DepartmentID`", "LEFT");
        $this->db->join("category c", "c.`ID`=i.`CategoryID`", "LEFT");
        $this->db->join("subcategory s", "s.`ID`=i.`SubCategoryID`", "LEFT");
        $this->db->join('alias a', 'a.`ItemID`=i.`ID`', 'LEFT');
        $this->db->group_by('i.`ID`');
        $this->db->limit('1000');

        $query = $this->db->get('`item` i');
        if ($query->num_rows() > 0)

            return $query->result();
        else
            return false;
    }

    // List of all the suppliers.
    public function suppliers()
    {
        $this->db->select('s.`ID`,s.`Code`,s.`SupplierName`,s.`ContactName`,s.`City`,s.`Address1`,s.`EmailAddress`,s.`Supplying`,s.`Terms`,s.`PhoneNumber`,s.`TaxNumber`,s.`Address2`,s.`AccountNumber`,s.`Withhold`,s.`TypeofGoods`,s.`Approved`');
        $query = $this->db->get('`supplier` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // List of all the suppliers.
    public function customers()
    {
        $this->db->select('c.`ID`,c.`AccountNumber`,c.`Company`,c.`EmailAddress`,c.`TaxNumber`,c.`Address`,c.`State`,c.`Title`,CONCAT(c.`FirstName`," ",c.`LastName`) AS ContactPerson,c.`PhoneNumber`,c.`AccountBalance`,c.`Approved`');

        $query = $this->db->get('`customer` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // Stock sheet method.
    public function transactions()
    {
        $this->db->select("i.`ItemLookupCode`,i.`Description`,d.`Name` as department,l.`Quantity`,c.`Name` AS category,s.`Name` as subcategory,i.`Cost`,i.`Price`,i.`UnitOfMeasure`");
        $this->db->join("inventorylocationitems l", "l.`ItemDBID`=i.`ID`");
        $this->db->join("department d", "d.`ID`=i.`DepartmentID`", "LEFT");
        $this->db->join("category c", "c.`ID`=i.`CategoryID`", "LEFT");
        $this->db->join("subcategory s", "s.`ID`=i.`SubCategoryID`", "LEFT");

        $query = $this->db->get('`item` i');
        if ($query->num_rows() > 0)

            return $query->result();
        else
            return false;
    }

    // Department listings.
    public function users()
    {
        $this->db->order_by('c.`Name`');

        $query = $this->db->get('`cashier` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Checking available stock takes and their current status.
    public function stocktakestatus()
    {
        $this->db->select("IFNULL(COUNT(s.`ID`),0) as stocktakestatus");
        $this->db->join('`configuration` c', 'c.`StoreID` = s.`StoreID`');
        $this->db->where('s.`Status`', 0);
        /** $this->db->WHERE('s.`Status`',0); */

        $query = $this->db->get('`stocktake` s');
        if ($query) {
            return $query->row()->stocktakestatus;
        }

        return false;
    }

    // Getting pending sync records.
    public function tempsheets_status()
    {
        $this->db->select("IFNULL(COUNT(s.`ID`),0) AS tempsheets_status");
        $this->db->join('`stocktake` t', 't.`ID`=s.`StocktakeID`');
        $this->db->where('s.`Status`', 0);
        $this->db->where('t.`Status`', 0);

        $query = $this->db->get('`tempsheets` s');
        if ($query) {
            return $query->row()->tempsheets_status;
        }

        return false;
    }

    // Checking whether there are records that haven't been synched.
    public function pendingsyncrecords()
    {
        $this->db->select("IFNULL(COUNT(s.`ID`),0) AS pendingsynchrecords");
        $this->db->join('`stocktake` t', 't.`ID`=s.`StocktakeID`');
        $this->db->where('s.`Status`', 0);
        $this->db->where('s.`Synched`', 0);
        $this->db->where('t.`Status`', 0);

        $query = $this->db->get('`stocksheets` s');
        if ($query) {
            return $query->row()->pendingsynchrecords;
        }

        return false;
    }

    // Stock take progress method.
    public function stocktakeprogress()
    {
        $this->db->select("IFNULL(COUNT(t.`ItemID`),0) as stocktakeprogress");
        $this->db->join('`configuration` c', 'c.`StoreID` = s.`StoreID`');
        $this->db->join('`tempsheets` t', 't.`StocktakeID`=s.`ID`');
        $this->db->where('s.`Status`', 0);

        $query = $this->db->get('`stocktake` s');
        if ($query) {
            return $query->row()->stocktakeprogress;
        }

        return false;
    }

    // Stock take progress method.
    public function stocktakesheets()
    {
        $this->db->select("IFNULL(COUNT(t.`ItemID`),0) as stocktakesheets");
        $this->db->join('`configuration` c', 'c.`StoreID` = s.`StoreID`');
        $this->db->join('`stocksheets` t', 't.`StocktakeID`=s.`ID`');
        $this->db->where('s.`Status`', 0);

        $query = $this->db->get('`stocktake` s');
        if ($query) {
            return $query->row()->stocktakesheets;
        }

        return false;
    }

    // Department listings.
    public function departments()
    {
        $this->db->select('d.`ID`,d.`Name`,d.`Code`,d.`pMargin`,d.`pComm`');
        $this->db->order_by('d.`Name`');

        $query = $this->db->get('`department` d');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Category listings.
    public function category()
    {
        $this->db->select('c.`ID`,c.`Name` as category');
        $this->db->order_by('c.`Name`');

        $query = $this->db->get('`category` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Sub Category listings */
    public function subcategory()
    {
        $this->db->select('s.`ID`,s.`Name` AS subcategory');
        $this->db->order_by('s.`Name`');

        $query = $this->db->get('`subcategory` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Category listings */
    public function get_categories_department($id)
    {
        $this->db->where('`DepartmentID`', $id);
        $this->db->order_by('Name');

        $query = $this->db->get('`category`');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Category listings */
    public function get_subcategories_department($id)
    {
        $this->db->where('`CategoryID`', $id);
        $this->db->order_by('Name');

        $query = $this->db->get('`subcategory`');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Inventory location name */
    public function storeconfig()
    {
        $this->db->select("l.`Description` AS storename");
        $this->db->join('`inventorylocation` l', 'l.`ID` = s.`StoreID`');

        $query = $this->db->get('`configuration` s');
        if ($query) {
            return $query->row()->storename;
        }

        return false;
    }

    // Freezing stocks for stock take method
    public function fstocks()
    {
        // Efficiently Insert Missing Items into inventorylocationitems
        $this->db->query("INSERT INTO inventorylocationitems (InventoryLocation, ItemDBID, Quantity) SELECT c.StoreID, i.ID, i.quantity FROM item i JOIN configuration c ON c.StoreID NOT IN (SELECT DISTINCT l.InventoryLocation FROM inventorylocationitems l)");
        // Insert Stock Take Header (Using Query Bindings for Security)
        $this->db->query("INSERT INTO stocktake (StoreID, CountingDate, InitiatedByID, InitiatedByName, Lastupdated) SELECT StoreID, NOW(), ?, ?, NOW() FROM configuration", [$this->session->userdata('ID'), $this->session->userdata('Name')]);

        $stocktakeid = $this->db->insert_id();
        // Insert Stock Take Entries with Proper Joins & Index Usage
        $query = $this->db->query("INSERT INTO stocktake_entry (StocktakeID, StoreID, DepartmentID, CategoryID, SubCategoryID, ItemID,ItemLookupCode, Description, BinLocation, tDate, OriginalQty, Lastupdated, Cost, Price) SELECT ?, c.StoreID, COALESCE(i.DepartmentID, 0), COALESCE(i.CategoryID, 0), COALESCE(i.SubCategoryID, 0),i.ID, i.ItemLookupCode, i.Description, i.BinLocation, NOW(), l.Quantity, NOW(), i.Cost, i.Price FROM item i JOIN inventorylocationitems l ON l.ItemDBID = i.ID JOIN configuration c ON c.StoreID = l.InventoryLocation", [$stocktakeid]);

        return $query ? true : false;
    }

    /** Showing the stock sheet that haven't been saved */
    public function pending_tempsheets()
    {
        $this->db->select("t.`StocktakeID` AS StocktakeID,t.`UserID`,t.`Shelf` AS bin,t.`Quantity`,e.`Cost`,e.`Price`,SUM(t.`Quantity`*e.`Cost`) AS costvalue,t.`CashierName` AS user,SUM(t.`Quantity`*e.`Price`) AS pricevalue");
        $this->db->join('`stocktake` s', 's.`ID`=t.`StocktakeID`');
        $this->db->join('`stocktake_entry` e', 'e.`ItemID`=t.`ItemID`');
        $this->db->where('t.`Status`', 0);
        $this->db->where('e.`StocktakeID`=t.`StocktakeID`');
        $this->db->where('s.`Status`', 0);
        $this->db->group_by('t.`CashierName`');

        $query = $this->db->get('`tempsheets` t');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // posting counted items and reseting all items not counted to 0.
    public function post_stocks()
    {
        $this->db->trans_start(); // Start Transaction
        $now = date('Y-m-d H:i:s');
        // reseting all skus to 0.
        $query = $this->db->query("update `item`i set i.`quantity`=0");
        $query = $this->db->query("update `inventorylocationitems`l set l.`Quantity`=0");
        // Updating uncounted SKUs
        $this->db->query("UPDATE `stocktake_entry` e  SET e.`QtyDiff` = IF(e.`OriginalQty` = 0, 0, COALESCE(e.`CountedQty` - e.`OriginalQty`, 0)),e.`Lastupdated` = '$now' WHERE e.`Status` = 0 AND e.`CountedDate` IS NULL");
        // Insert into physicalinventory
        $this->db->query("INSERT INTO `physicalinventory` (`InventoryLocation`, `StoreID`, `OpenTime`, `CloseTime`, `Status`, `LastRefresh`, `Description`, `uUser`) SELECT s.`StoreID`, s.`StoreID`, s.`CountingDate`, NOW(), 2, NOW(), 'Stock Take', '" . $this->session->userdata('Name') . "' FROM `stocktake` s  WHERE s.`Status` = 0");

        $physical = $this->db->insert_id();
        // Fetch stock take ID
        $StockTakeID = $this->db->query("SELECT s.`ID` AS StockTakeID FROM `stocktake` s WHERE s.`Status` = 0")->row()->StockTakeID;
        // Insert into physicalinventoryentry
        $this->db->query("INSERT `physicalinventoryentry`(`StoreID`,`PhysicalInventoryID`,`ReasonCodeID`,`CountTime`,`ItemID`,`BinLocation`,`Price`,`Cost`,`QuantityCounted`,`QuantityAdjusted`,`QuantityRefreshed`) SELECT e.`StoreID`,'$physical',97 AS ReasonCodeID,`CountedDate`,e.`ItemID`,e.`BinLocation`,e.`Price`,e.`Cost`,e.`CountedQty` AS QuantityCounted,(e.`QtyDiff`) AS QuantityAdjusted,e.`CountedQty` AS QuantityRefreshed FROM `stocktake_entry`e WHERE e.`Status`= 0 AND e.`StocktakeID`='$StockTakeID' AND e.`QtyDiff`<>0 ");
        // Update stock quantities in the item table
        $this->db->query("UPDATE `item` i JOIN `stockshets_complete` e ON e.`ItemID` = i.`ID` SET i.`quantity` = e.`Quantity` WHERE e.`Status` = 0 AND e.`StocktakeID` = '$StockTakeID'");
        // Update stock quantities in inventorylocationitems table
        $this->db->query("UPDATE `inventorylocationitems` l JOIN `item` i ON i.`ID` = l.`ItemDBID` SET l.`Quantity` = i.`quantity` WHERE l.`InventoryLocation` IN (SELECT c.`StoreID` FROM `configuration` c)");
        // Close the stock take
        $this->db->query("UPDATE `stocktake` s SET s.`Status` = 1,s.`Committed` = '" . $this->session->userdata('ID') . "',s.`CommittedName` = '" . $this->session->userdata('Name') . "',s.`Lastupdated` = NOW(),s.`DateCommitted` = NOW()");
        // Close stock take details
        $this->db->query("UPDATE `stocktake_entry` e SET e.`Status` = 1, e.`Lastupdated` = NOW(), e.`CountedDate` = NOW()");
        // Update stock take sheets with closed status
        $this->db->query("UPDATE `stocksheets` s SET s.`Status` = 1, s.`cDate` = NOW()");
        // Update physicalinventory code with formatted ID
        $this->db->query("UPDATE `physicalinventory` p SET p.`Code` = LPAD('$physical', 6, '0')");

        $this->db->trans_complete(); // Commit or Rollback
        return $this->db->trans_status();

    }

    // Posting only the counted items leaving uncounted as they were.
    public function post_counted()
    {
        // Start a transaction to ensure data consistency
        $this->db->trans_start();
        // Insert stock take into physicalinventory table
        $this->db->query("INSERT INTO physicalinventory (InventoryLocation, StoreID, OpenTime, CloseTime, Status, LastRefresh, Description, uUser) SELECT s.StoreID, s.StoreID, s.CountingDate, NOW(), 2, NOW(), 'Stock Take', ? FROM stocktake s WHERE s.Status = 0", [$this->session->userdata('Name')]);
        $physical = $this->db->insert_id(); // Get last inserted ID
        // Pick the stock take record
        $StockTakeID = $this->db->query("SELECT ID FROM stocktake WHERE Status = 0 LIMIT 1")->row()->ID ?? null;
        if (!$StockTakeID) {
            $this->db->trans_rollback();
            return false;
        }
        // Insert into physicalinventoryentry
        $this->db->query("INSERT INTO physicalinventoryentry (StoreID, PhysicalInventoryID, ReasonCodeID, CountTime, ItemID, BinLocation, Price, Cost, QuantityCounted, QuantityAdjusted, QuantityRefreshed) SELECT e.StoreID, ?, 97, e.CountedDate, e.ItemID, e.BinLocation, e.Price, e.Cost, e.CountedQty, e.QtyDiff, e.CountedQty FROM stocktake_entry e WHERE e.Status = 0 AND e.StocktakeID = ? AND e.QtyDiff != 0 AND e.CountedDate IS NOT NULL", [$physical, $StockTakeID]);
        // Update item stock quantities
        $this->db->query("UPDATE item i JOIN stocktake_entry e ON e.ItemID = i.ID SET i.quantity = (i.quantity + e.QtyDiff) WHERE e.Status = 0 AND e.CountedDate IS NOT NULL AND e.StocktakeID = ?", [$StockTakeID]);
        // Update inventory location items
        $this->db->query("UPDATE inventorylocationitems l JOIN item i ON i.ID = l.ItemDBID SET l.Quantity = i.quantity WHERE l.InventoryLocation IN (SELECT StoreID FROM configuration)");
        // Close the stock take
        $this->db->query("UPDATE stocktake SET Status = 1, Committed = ?, CommittedName = ?, Lastupdated = NOW(), DateCommitted = NOW() WHERE ID = ?", [$this->session->userdata('ID'), $this->session->userdata('Name'), $StockTakeID]);
        // Close stock take details
        $this->db->query("UPDATE stocktake_entry SET Status = 1, Lastupdated = NOW(), CountedDate = NOW() WHERE StocktakeID = ?", [$StockTakeID]);
        // Update stock take sheets
        $this->db->query("UPDATE stocksheets SET Status = 1, cDate = NOW() WHERE StocktakeID = ?", [$StockTakeID]);
        // Update physicalinventory code formatting
        $this->db->query("UPDATE physicalinventory SET Code = LPAD(?, 6, '0') WHERE ID = ?", [$physical, $physical]);
        // Complete transaction
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /** Specific sheet details */
    public function product($id)
    {
        $this->db->select('`ItemID`,`ItemLookupCode`,`Quantity`,`Description`,DATE(`CountedDate`) AS tDate,`Username`,`bin`');
        $this->db->from('stocksheets');
        $this->db->where('ItemID', $id);
        $this->db->where('Status', 0);

        $query = $this->db->get();

        return $query->result();
    }

    // Pending sheet(sheets that have not been saved grouped by respective users).
    public function products($id)
    {
        $this->db->select('`ItemID`,`ItemLookupCode`,`Quantity`,`Itemdescription` AS Description,DATE(`tTime`) AS tDate,`CashierName` AS Username,`Shelf` as bin');
        $this->db->from('`tempsheets`');
        $this->db->where('UserID', $id);
        $this->db->where('Status', 0);
        $this->db->group_by('ID');

        $query = $this->db->get();

        return $query->result();
    }

    // Getting fed item description
    public function code_desc($id)
    {
        $query = $this->db->query("SELECT i.`Description` FROM  `item`i  WHERE i.`ItemLookupCode`= $id UNION SELECT i.`Description` FROM `alias` a JOIN `item`i ON i.`ID`=a.`ItemID` WHERE a.`Alias`= $id ");

        return $query->row();
    }
    //
    // records filter counts.

    public function stockscount()
    {
        $this->db->where('s.Status', 0);
        return $this->db->count_all('`stocktake_entry` s');
    }

    // Records filter counts.

    public function filteredstocks($search, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->_stocks($search, 0, 'asc', $DepartmentID, $CategoryID, $SubCategoryID);
        //log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->count_all_results();
    }


    // Getting the counted SKUs records.
    public function _stocks($search, $order_by, $order_dir, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->db->select('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,(s.Cost*s.OriginalQty) as availableTotal,(s.Cost*s.CountedQty) as countedTotal,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');
        $this->db->from('stocktake_entry s');
        $this->db->join('department d', 'd.`ID`=s.`DepartmentID`', 'left');
        $this->db->join('category c', 'c.`ID`=s.`CategoryID`', 'left');
        $this->db->join('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'left');
        $this->db->join('`alias` a', 'a.`ItemID`=s.`ItemID`', 'left');
        $this->db->group_by('s.`ID`');
        $this->db->where('s.`CountedQty`>', 0);
        $this->db->where('s.Status', 0);

        if ($DepartmentID) $this->db->where('d.ID', $DepartmentID);
        if ($CategoryID) $this->db->where('c.ID', $CategoryID);
        if ($SubCategoryID) $this->db->where('e.ID', $SubCategoryID);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('s.`ID`', $search)
                ->or_like('s.`ItemID`', $search)
                ->or_like('s.`ItemLookupCode`', $search)
                ->or_like('a.`Alias`', $search)
                ->or_like('s.`Description`', $search)
                ->group_end();
        }
        $columns = ['ID', 'ItemID', 'ItemLookupCode', 'Description'];
        if (isset($columns[$order_by])) {
            $this->db->order_by($columns[$order_by], $order_dir);
        } else {
            $this->db->order_by('s.`ID`', 'asc');
        }
    }

    // filtered data.
    public function stocks($search, $order_by, $order_dir, $start, $length, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->_stocks($search, $order_by, $order_dir, $DepartmentID, $CategoryID, $SubCategoryID);
        // apply limits for pagination.
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        // log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->get()->result();// Fetch filtered and paginated results.
    }

    public function uncountedcount()
    {
        $this->db->where('s.Status', 0);
        $this->db->where('s.`CountedQty`', 0);
        return $this->db->count_all_results('`stocktake_entry` s');
    }

    // Records filter counts.

    public function filtereduncounted($search, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->_uncounted($search, 0, 'asc', $DepartmentID, $CategoryID, $SubCategoryID);
        //log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->count_all_results();
    }

    // Uncounted SKUs.
    public function _uncounted($search, $order_by, $order_dir, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

        $this->db->from('stocktake_entry s');
        $this->db->join('department d', 'd.`ID`=s.`DepartmentID`', 'left');
        $this->db->join('category c', 'c.`ID`=s.`CategoryID`', 'left');
        $this->db->join('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'left');
        $this->db->join('`alias` a', 'a.`ItemID`=s.`ItemID`', 'left');
        $this->db->where('s.`CountedQty`', 0);
        $this->db->where('s.Status', 0);
        $this->db->group_by('s.`ID`');

        if ($DepartmentID) $this->db->where('d.ID', $DepartmentID);
        if ($CategoryID) $this->db->where('c.ID', $CategoryID);
        if ($SubCategoryID) $this->db->where('e.ID', $SubCategoryID);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('s.`ID`', $search)
                ->or_like('s.`ItemID`', $search)
                ->or_like('s.`ItemLookupCode`', $search)
                ->or_like('a.`Alias`', $search)
                ->or_like('s.`Description`', $search)
                ->group_end();
        }
        $columns = ['ID', 'ItemID', 'ItemLookupCode', 'Description'];
        if (isset($columns[$order_by])) {
            $this->db->order_by($columns[$order_by], $order_dir);
        } else {
            $this->db->order_by('s.`ID`', 'asc');
        }
    }

    // filtered data.
    public function uncounted($search, $order_by, $order_dir, $start, $length, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->_uncounted($search, $order_by, $order_dir, $DepartmentID, $CategoryID, $SubCategoryID);
        // apply limits for pagination.
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        // log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->get()->result();// Fetch filtered and paginated results.
    }

    // un-counted stocks excel.
    public function uncounted_excel()
    {
        $this->db->select('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,b.SupplierName,s.`ItemID` AS ItemID,s.ItemLookupCode,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`tDate`) AS CountedDate,a.`Alias`,(s.OriginalQty*s.Cost) as availableValue,(s.OriginalQty*s.Price) as priceValue');
        $this->db->join('item i', 'i.ID=s.ItemID');
        $this->db->join('supplier b', 'b.ID=i.`SupplierID`', 'left');
        $this->db->join('department d', 'd.`ID`=s.`DepartmentID`', 'left');
        $this->db->join('category c', 'c.`ID`=s.`CategoryID`', 'left');
        $this->db->join('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'left');
        $this->db->join('`alias` a', 'a.`ItemID`=s.`ItemID`', 'left');
        $this->db->where('s.`CountedQty`', 0);
        $this->db->where('s.Status', 0);
        $this->db->group_by('s.`ID`');

        $query = $this->db->get('`stocktake_entry` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Case 1: Where a user is quering specifics(Department,category & subcategory).
    public function counted_stocks($DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->db->select('d.Name AS department,c.Name AS category,e.Name AS subcategory,s.ItemID AS ItemID,s.ItemLookupCode AS Code,s.Description AS description,s.Cost AS Cost,s.OriginalQty AS OriginalQty,s.CountedQty AS CountedQty,DATE(s.CountedDate) AS CountedDate');
        $this->db->from('stocktake_entry s');
        $this->db->join('department d', 'd.ID = s.DepartmentID', 'LEFT');
        $this->db->join('category c', 'c.ID = s.CategoryID', 'LEFT');
        $this->db->join('subcategory e', 'e.ID = s.SubCategoryID', 'LEFT');
        // Apply filtering conditions dynamically
        if ($DepartmentID != 0) {
            $this->db->where('d.ID', $DepartmentID);
        }
        if ($CategoryID != 0) {
            $this->db->where('c.ID', $CategoryID);
        }
        if ($SubCategoryID != 0) {
            $this->db->where('e.ID', $SubCategoryID);
        }
        // Common conditions
        $this->db->where('s.CountedQty >', 0);
        $this->db->where('s.Status', 0);
        // Execute query
        $query = $this->db->get();
        // Return results or false if empty
        return ($query->num_rows() > 0) ? $query->result() : false;

    }

    // records filter counts.

    public function holdingscount()
    {
        $this->db->where('s.Status', 0);
        $this->db->where('s.`OriginalQty`>=', 0);
        return $this->db->count_all_results('`stocktake_entry` s');
    }

    // Records filter counts.

    public function filteredholdings($search, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->_holdings($search, 0, 'asc', $DepartmentID, $CategoryID, $SubCategoryID);
        //log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->count_all_results();
    }

    // Current stock list
    public function _holdings($search, $order_by, $order_dir, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->db->SELECT('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` as itemcode,e.`Description`,e.`BinLocation` as bin,e.`Price`,e.`Cost`,e.`OriginalQty`,DATE(s.`CountingDate`) as CountingDate,(e.Cost*e.OriginalQty) as availableTotal,(e.Cost*e.CountedQty) as countedTotal,e.`CountedQty`');
        //$this->db->from('`stocktake` s');
        $this->db->from('`stocktake_entry` e');
        $this->db->join('`stocktake` s', 's.`ID` = e.`StocktakeID`');
        $this->db->join('`alias` a', 'a.`ItemID`=e.`ItemID`', 'left');
        $this->db->where('s.`Status`', 0);
        $this->db->where('e.`OriginalQty`>=', 0);
        $this->db->group_by('e.`ItemID`');

        if ($DepartmentID) $this->db->where('e.`DepartmentID`', $DepartmentID);
        if ($CategoryID) $this->db->where('e.`CategoryID`', $CategoryID);
        if ($SubCategoryID) $this->db->where('e.`SubCategoryID`', $SubCategoryID);

        if (!empty($search)) {
            $this->db->group_start()
                ->like('e.`ID`', $search)
                ->or_like('e.`ItemID`', $search)
                ->or_like('e.`ItemLookupCode`', $search)
                ->or_like('a.`Alias`', $search)
                ->or_like('e.`Description`', $search)
                ->group_end();
        }
        $columns = ['ID', 'ItemID', 'ItemLookupCode', 'Description'];
        if (isset($columns[$order_by])) {
            $this->db->order_by($columns[$order_by], $order_dir);
        } else {
            $this->db->order_by('e.`ItemID`', 'asc');
        }
    }

    // filtered data.
    public function holdings($search, $order_by, $order_dir, $start, $length, $DepartmentID, $CategoryID, $SubCategoryID)
    {
        $this->_holdings($search, $order_by, $order_dir, $DepartmentID, $CategoryID, $SubCategoryID);
        // apply limits for pagination.
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        //log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->get()->result();// Fetch filtered and paginated results.
    }

// stock holding report in excel.
    public function holdings_excel()
    {
        $this->db->select('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` as itemcode,e.`Description`,e.`BinLocation` as bin,e.`Price`,e.`Cost`,e.`OriginalQty`,DATE(s.`CountingDate`) as CountingDate,e.`CountedQty`,(e.CountedQty*e.Cost) as countedValue,(e.Cost*e.OriginalQty) as availableValue');
        $this->db->join('`stocktake_entry` e', 's.`ID` = e.`StocktakeID`');
        $this->db->join('`alias` a', 'a.`ItemID`=e.`ItemID`', 'left');
        $this->db->where('s.`Status`', 0);
        $this->db->where('e.`OriginalQty`>=', 0);
        $this->db->group_by('e.`ID`');
        $this->db->order_by('e.`ItemID`', 'desc');
        log_message('debug', 'Last Query: ' . $this->db->last_query());
        $query = $this->db->get('`stocktake` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // stock take report excel.
    public function stocks_excel()
    {
        $this->db->select('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

        $this->db->join('`department` d', 'd.`ID`=s.`DepartmentID`', 'left');
        $this->db->join('`category` c', 'c.`ID`=s.`CategoryID`', 'left');
        $this->db->join('`subcategory` e', 'e.`ID`=s.`SubCategoryID`', 'left');
        $this->db->join('`alias` a', 'a.`ItemID`=s.`ItemID`', 'left');
        $this->db->group_by('s.`ID`');
        $this->db->where('s.`CountedQty`>', 0);
        $this->db->where('s.Status', 0);

        $query = $this->db->get('`stocktake_entry` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /**  */
    public function historysearch($user, $startdate, $enddate)
    {
        $this->db->select("l.`Description`,s.`ID`,s.`Status`,s.`CountingDate`,s.`InitiatedByName`,s.`CommittedName`,s.`DateCommitted`");
        $this->db->join('`inventorylocation` l', 'l.`ID`=s.`StoreID`');
        /** $this->db->WHERE('s.`Status`<>', 0); */
        $this->db->where('s.`Committed`', $user);
        $this->db->where('DATE(s.`DateCommitted`)>=', $startdate);
        $this->db->where('DATE(s.`DateCommitted`)<=', $enddate);
        $this->db->order_by('s.`ID`', 'ASC');

        $query = $this->db->get('`stocktake` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Posting stock sheet after the user confirms */
    public function post_sheets()
    {
        $query = $this->db->query("INSERT  `stocksheets`(`StocktakeID`,`ItemID`,`ItemLookupCode`,`Description`,`bin`,`Quantity`,`CountedDate`,`UserID`,`Username`,`Reasoncode`) SELECT `StocktakeID`,`ItemID`,`ItemLookupCode`,`Itemdescription`,`Shelf`,`Quantity`,`tTime`,`UserID`,`CashierName`,`Reasoncode` FROM `tempsheets`s JOIN `stocktake`t ON t.`ID`=s.`StocktakeID` WHERE s.`Status`=0 AND s.`UserID`='" . $this->session->userdata('ID') . "' AND t.`Status`=0 ");

        $id = $this->db->insert_id();
        /** Return false; */
        $query0 = $this->db->query("SELECT s.`bin` as bin FROM  `stocksheets`s WHERE s.`ID`=$id ");

        $bin = $query0->row()->bin;

        $query1 = $this->db->query("UPDATE `tempsheets` t SET t.`Status`=1 WHERE t.`Shelf`='$bin' AND t.`UserID`='" . $this->session->userdata('ID') . "'");
        if ($query1) {
            return true;
        }

        return false;
    }

    /** Synchronise stock take details */
    public function sync_stocks()
    {
        $this->db->trans_start(); // Start transaction
        // Get count of remaining entries in tempsheets
        $left_entries = $this->db->select('COUNT(t.ID) AS left_entries')
            ->from('tempsheets t')
            ->join('stocktake s', 's.ID = t.StocktakeID')
            ->where('t.Status', 0)
            ->where('s.Status', 0)
            ->get()
            ->row()
            ->left_entries;

        if ($left_entries == 0) {
            // Fetch active stocktake ID and counting date
            $query = $this->db->select('ID AS stocktakeid, CountingDate')
                ->where('Status', 0)
                ->get('stocktake');

            if ($query->num_rows() == 0) {
                $this->db->trans_complete(); // Ensure transaction is closed
                return false; // No active stocktake found
            }

            $row = $query->row();
            $stocktakeid = $row->stocktakeid;
            $CountingDate = $row->CountingDate;
            // Delete stocktake_entry records before syncing
            $this->db->where('StocktakeID', $stocktakeid)->delete('stocktake_entry');
            // Insert all missing SKU records in stocktake_entry using parameterized query
            $insert_query = "INSERT INTO stocktake_entry (StocktakeID, StoreID, DepartmentID, CategoryID, SubCategoryID, ItemID, ItemLookupCode, Description, BinLocation, tDate, OriginalQty, Lastupdated, Cost, Price) SELECT ?, c.StoreID, IFNULL(i.DepartmentID, 0), IFNULL(i.CategoryID, 0), IFNULL(i.SubCategoryID, 0), i.ID, i.ItemLookupCode, i.Description, BinLocation, ?, l.Quantity, NOW(), i.Cost, i.Price FROM item i JOIN inventorylocationitems l ON l.ItemDBID = i.ID JOIN  configuration c ON c.StoreID = l.InventoryLocation";
            $this->db->query($insert_query, [$stocktakeid, $CountingDate]);
            // Clear stocksheets_complete before syncing
            $this->db->truncate('stockshets_complete'); // Use truncate for better performance
            // Insert aggregated stocktake records into stocksheets_complete
            $aggregate_query = "INSERT INTO stockshets_complete (StocktakeID, ItemID, Quantity, Status) SELECT e.StocktakeID, e.ItemID, IFNULL(SUM(e.Quantity), 0), e.Status FROM stocksheets e WHERE e.Status = 0 GROUP BY e.ItemID ORDER BY e.ItemID";
            $this->db->query($aggregate_query);

            // Update counts in stocktake_entry
            $update_query = "UPDATE stocktake_entry s JOIN stockshets_complete c ON c.ItemID = s.ItemID SET s.CountedQty = c.Quantity, s.Lastupdated = NOW(), s.CountedDate = NOW() WHERE s.Status = 0 AND s.ItemID IN (SELECT DISTINCT ItemID FROM stocksheets)";
            $this->db->query($update_query);

            // Update quantity difference in stocktake_entry
            $this->db->set('QtyDiff', 'IFNULL(CountedQty - OriginalQty, 0)', FALSE)
                ->set('Lastupdated', 'NOW()', FALSE)
                ->where('Status', 0)
                ->where('CountedDate IS NOT NULL')
                ->update('stocktake_entry');

            // Mark stocksheets as synced
            $this->db->where('StocktakeID', $stocktakeid)
                ->set('Synched', 1)
                ->update('stocksheets');

            $this->db->trans_complete(); // Commit transaction
            return $this->db->trans_status(); // Return true if all queries succeeded
        } else {
            $this->db->trans_complete(); // Ensure transaction is closed
            return 9; // Return error code if entries exist
        }
    }

    /** Un-doing stock take that has been initiated */
    public function undofreeze()
    {
        $this->db->trans_start(); // Start transaction

        // Securely fetch stocktake ID
        $query0 = $this->db->select('ID AS stocktakeid')
            ->where('Status', 0)
            ->get('stocktake');

        if ($query0->num_rows() === 0) {
            $this->db->trans_complete(); // Ensure rollback
            return false; // No stocktake found to delete
        }

        $stocktakeid = $query0->row()->stocktakeid;

        // Securely fetch min_entry ID for stocktake_entry
        $querye = $this->db->select_min('ID', 'min_entry')
            ->where('StocktakeID', $stocktakeid)
            ->get('stocktake_entry');

        $min_entry = ($querye->num_rows() > 0 && $querye->row()->min_entry) ? $querye->row()->min_entry : 1; // Default to 1 if no entries exist

        // Batch Deletions with Safe Bindings
        $this->db->where('ID', $stocktakeid)->delete('stocktake');
        $this->db->where('StocktakeID', $stocktakeid)->where('Status', 0)->delete('stocktake_entry');
        $this->db->where('StocktakeID', $stocktakeid)->delete('stocksheets');
        $this->db->where('StocktakeID', $stocktakeid)->delete('tempsheets');

        // Reset Auto Increment (Fixed issue: No bound parameters in ALTER TABLE)
        $this->db->query("ALTER TABLE stocktake AUTO_INCREMENT = " . (int)$stocktakeid);
        $this->db->query("ALTER TABLE stocktake_entry AUTO_INCREMENT = " . (int)$min_entry);

        $this->db->trans_complete(); // Commit or rollback transaction

        return $this->db->trans_status();
    }

    /** Updating tempsheet entry details  */
    public function updatedetail()
    {
        $id = $this->input->post('action');

        $data['ItemLookupCode'] = $this->input->post('item_code');
        $data['Quantity'] = $this->input->post('quantity');
        $data['CountedDate'] = date('Y-m-d H:i:s');
        $data['UserID'] = $this->session->userdata('ID');
        $data['Username'] = ucwords($this->session->userdata('Name'));
        $data['bin'] = strtoupper($this->input->post('bin'));

        if ($id > 0) {
            $this->db->where('ID', $id);
            $query = $this->db->update('stocksheets', $data);
            return 1;
        } else {
            return 2;
        }
    }

    /** Deleting stocksheet entry */
    public function del_sheet_entry($id)
    {
        // Fetch the entry quantity and ItemID securely
        $query = $this->db->select('Quantity, ItemID')
            ->where('ID', $id)
            ->get('stocksheets');

        if ($query->num_rows() === 0) {
            return false; // No stocktake found
        }

        $row = $query->row();
        $Quantity = $row->Quantity;
        $itemID = $row->ItemID;

        // Get current CountedQty securely
        $this->db->select('CountedQty');
        $this->db->where(['Status' => 0, 'ItemID' => $itemID]);
        $countedQuery = $this->db->get('stocktake_entry');

        $countedQty = $countedQuery->num_rows() > 0 ? $countedQuery->row()->CountedQty : 0;

        // Ensure enough stock before updating
        if ($countedQty >= $Quantity) {
            $this->db->set('CountedQty', "CountedQty - {$Quantity}", false); // Prevent escaping, keeps raw SQL
            $this->db->where(['Status' => 0, 'ItemID' => $itemID]);
            $this->db->update('stocktake_entry');
        }

        // Delete entry from stocksheets securely
        $this->db->where('ID', $id);
        $deleted = $this->db->delete('stocksheets');

        return $deleted;

    }

    /** Feeding stock take data */
    public function stock_take()
    {
        $this->db->trans_start(); // Start transaction
        $id = (int)$this->input->post('action'); // Typecast for security
        $data = [
            'ItemLookupCode' => $this->input->post('item_code', true),
            'Quantity' => (int)$this->input->post('quantity'),
            'tTime' => date('Y-m-d H:i:s'),
            'UserID' => (int)$this->session->userdata('ID'),
            'CashierName' => ucwords($this->session->userdata('Name')),
            'Shelf' => strtoupper($this->input->post('bin', true)),
            'Reasoncode' => (int)$this->input->post('reasoncode')
        ];
        // Ensure Shelf is valid
        if (empty($data['Shelf']) || $data['Shelf'] === 'NULL' || $data['Shelf'] === '0') {
            return 8; // Invalid bin/shelf
        }
        // Check if item exists in `item` or `alias` tables
        $query = $this->db->query("select ID from item where ItemLookupCode = ? UNION select ItemID from alias where Alias = ? LIMIT 1",
            [$data['ItemLookupCode'], $data['ItemLookupCode']]
        );
        if ($query->num_rows() === 0) {
            return 3; // Item does not exist
        }
        if ($id > 0) {
            // Update existing tempsheets entry
            $this->db->where('ID', $id);
            $this->db->update('tempsheets', $data);

            if ($this->db->affected_rows() > 0) {
                $this->tempsheet_update($id, $data['ItemLookupCode']);
                $this->db->trans_complete(); // Commit transaction
                return 2; // Successfully updated
            }
        } else {
            // Check user's pending sheet count
            $entries = $this->db->query("select COUNT(s.ID) AS entries from tempsheets s join stocktake t on t.ID = s.StocktakeID where s.UserID = ? and s.CashierName = ? and s.Status = 0 and t.Status = 0", [$data['UserID'], $data['CashierName']])->row()->entries ?? 0;

            if ($entries >= 15) {
                return 9; // Max sheet entries reached
            }
            // Check if item is already in pending sheets
            $query1 = $this->db->get_where('tempsheets', ['ItemLookupCode' => $data['ItemLookupCode'],
                'Shelf' => $data['Shelf'], 'Status' => 0, 'CashierName' => $data['CashierName']
            ]);

            if ($query1->num_rows() === 0) {
                // Validate shelf assignment consistency
                $query2 = $this->db->query("select s.Shelf as shelfname from tempsheets s join stocktake t on t.ID = s.StocktakeID where s.Status = 0 and t.Status = 0 and s.CashierName = ? LIMIT 1", [$data['CashierName']]);

                $shelfname = $query2->row()->shelfname ?? null;
                if ($shelfname === $data['Shelf'] || is_null($shelfname)) {
                    // Insert new record into tempsheets
                    $this->db->insert('tempsheets', $data);
                    $id = $this->db->insert_id();
                    $this->update_stocksheets($id, $data['ItemLookupCode']);
                    $this->db->trans_complete(); // Commit transaction
                    return 1; // Successfully inserted
                } else {
                    return 5; // Shelf mismatch error
                }
            } else {
                // Log repeated item code in tempduplicate table
                $this->db->insert('tempduplicate', ['ItemLookupCode' => $data['ItemLookupCode'], 'Quantity' => $data['Quantity'], 'tTime' => $data['tTime'], 'UserID' => $data['UserID'], 'CashierName' => $data['CashierName']]);
                return 4; // Duplicate item logged
            }
        }

        $this->db->trans_complete(); // Commit or rollback transaction
        return $this->db->trans_status() ? true : false;

    }

    /** Updating the existing SKU with the found quantities */
    public function updatecode()
    {
        $this->db->trans_start(); // Start transaction
        // Securely update `tempsheets` quantities using JOIN
        $this->db->query("update tempsheets s join tempduplicate d on d.ItemLookupCode = s.ItemLookupCode set s.Quantity = s.Quantity + d.Quantity,s.tTime = NOW() where s.UserID = d.UserID and s.CashierName = d.CashierName and s.Status = 0");
        // Securely delete processed `tempduplicate` records
        $this->db->where(['UserID' => $this->session->userdata('ID'), 'CashierName' => $this->session->userdata('Name')]);
        $this->db->delete('tempduplicate');

        $this->db->trans_complete(); // Commit or rollback transaction
        return $this->db->trans_status() ? 6 : 2;

    }

    /**Delete mistakanely feed entry */
    public function deletebinentry($id)
    {
        $query = $this->db->query("DELETE d FROM `tempsheets`d WHERE d.`UserID`='" . $this->session->userdata('ID') . "' AND d.`CashierName` = '" . $this->session->userdata('Name') . "' AND d.`ID`=$id ");

        if ($query) {
            return 1;
        } else {
            return 0;
        }
    }

    /** Cancelling the item update */
    public function cancelcode()
    {
        $query = $this->db->query("DELETE d FROM `tempduplicate`d WHERE d.`UserID`='" . $this->session->userdata('ID') . "' AND d.`CashierName` = '" . $this->session->userdata('Name') . "' ");

        if ($query) {
            return 4;
        } else {
            return 2;
        }
    }

    /** Update  counted item  in stocksheets table */
    public function update_stocksheets($id, $ItemLookupCode)
    {
        // When the user keys in ItemLookupCode
        $query = $this->db->query("UPDATE `tempsheets`s JOIN `item`i ON s.`ItemLookupCode`=i.`ItemLookupCode` SET s.`ItemID`=i.`ID`,s.`Itemdescription`=i.`Description` WHERE s.`ItemLookupCode`= '$ItemLookupCode' AND s.`ID`=$id");

        // When the user keys in an Alias code
        $query0 = $this->db->query("UPDATE `tempsheets`s JOIN `alias`a ON s.`ItemLookupCode`=a.`Alias` JOIN `item`i ON a.`ItemID`=i.`ID` SET s.`ItemID`=i.`ID`,s.`Itemdescription`=i.`Description` WHERE a.`Alias`= '$ItemLookupCode' AND s.`ID`=$id");

        // Updating sheet entry StocktakeID
        $query1 = $this->db->query("UPDATE `tempsheets`s SET s. `StocktakeID` =(SELECT s.`ID` FROM `stocktake`s WHERE s.`Status`=0)
        WHERE s.`StocktakeID`=0");
    }

    // Updating tempsheets when an entry is updated
    public function tempsheet_update($id, $ItemLookupCode)
    {
        // When a user updates an item with ItemLookupCode.
        $query = $this->db->query("UPDATE `tempsheets`s JOIN `stocktake_entry`e ON s.`ItemLookupCode`=e.`ItemLookupCode` SET s.`ItemID`=e.`ItemID`,s.`Itemdescription`=e.`Description` WHERE s.`ItemLookupCode`= '$ItemLookupCode' AND s.`ID`=$id");
        // When a user updates an item with an Alias code
        $query0 = $this->db->query("UPDATE `tempsheets`s JOIN `alias`a ON s.`ItemLookupCode`=a.`Alias` JOIN `item`i ON a.`ItemID`=i.`ID` SET s.`ItemID`=i.`ID`,s.`Itemdescription`=i.`Description` WHERE a.`Alias`= '$ItemLookupCode' AND s.`ID`=$id");

        // Updating sheet entry StocktakeID
        // $query1 = $this->db->query("UPDATE `tempsheets`s SET s. `StocktakeID` =(SELECT s.`ID` FROM `stocktake`s WHERE s.`Status`=0)
        // AND s.`StocktakeID`=0");
    }

//
    public function binscount()
    {
        $this->db->where('s.Status', 0);
        return $this->db->count_all_results('`stocksheets` s');
    }

    // Records filter counts.

    public function filteredbins($search)
    {
        $this->_binsheets($search, 0, 'asc');
        //log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->count_all_results();
    }

    // Bin sheets report
    public function _binsheets($search, $order_by, $order_dir)
    {
        $this->db->select("s.`bin` AS bin,s.`ItemLookupCode` AS itemcode,s.`Description`,s.`CountedDate` AS fedtime,i.`Cost`,i.`Price`,s.`Quantity`,s.`Username` AS username ");
        $this->db->from('`stocksheets` s');
        $this->db->join('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->where('s.`Status`', 0);
        $this->db->order_by('s.`bin`');

        if (!empty($search)) {
            $this->db->group_start()
                ->like('s.`ID`', $search)
                ->or_like('s.`ItemID`', $search)
                ->or_like('s.`ItemLookupCode`', $search)
                ->or_like('s.`Description`', $search)
                ->group_end();
        }
        $columns = ['ID', 'ItemID', 'ItemLookupCode', 'Description'];
        if (isset($columns[$order_by])) {
            $this->db->order_by($columns[$order_by], $order_dir);
        } else {
            $this->db->order_by('s.`ItemID`', 'asc');
        }
    }

    // rendering filtered data to the controller.
    public function binsheets($search, $order_by, $order_dir, $start, $length)
    {
        $this->_binsheets($search, $order_by, $order_dir);
        // apply limits for pagination.
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        // log_message('debug', 'Last Query: ' . $this->db->last_query());
        return $this->db->get()->result();// Fetch filtered and paginated results.
    }

    // bin sheets excel report.
    public function binsheets_excel()
    {
        $this->db->select("s.`bin` AS bin,s.`ItemLookupCode` AS itemcode,s.`Description`,s.`CountedDate` AS fedtime,i.`Cost`,i.`Price`,s.`Quantity`,s.`Username` AS username ");
        $this->db->join('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->where('s.`Status`', 0);
        $this->db->order_by('s.`bin`');
        $query = $this->db->get('`stocksheets` s');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // number of skus in the stock sheets.
    public function sheetscount()
    {
        $this->db->where('s.`Status`', 0);
        return $this->db->count_all_results('`stocksheets` s');
    }

    // counting filtered sheets records.
    public function filteredsheets($search, $LookupCode)
    {
        $this->_syncstocksheets($search, 0, 'asc', $LookupCode);
        return $this->db->count_all_results();
    }

    // Show all the items that are pending synching.
    public function _syncstocksheets($search, $order_by, $order_dir, $LookupCode)
    {
        $this->db->select("s.ID,s.Description,s.bin,s.Username,s.Quantity,a.`Alias`,i.`ItemLookupCode`,i.`Cost`,(s.Quantity*i.Cost) as costValue,i.`Price`,(i.`Price`*s.`Quantity`) as priceValue");
        $this->db->from('`stocksheets` s');
        $this->db->join('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->join('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
        $this->db->where('s.`Status`', 0);
        $this->db->group_by('s.`ID`');

        if ($LookupCode != "") {
            $this->db->where('s.`ItemLookupCode`', $LookupCode);
            $this->db->or_where('a.Alias', $LookupCode);
        }

        if (!empty($search)) {
            $this->db->group_start()
                ->like('s.`ID`', $search)
                ->or_like('s.`ItemLookupCode`', $search)
                ->or_like('s.`ItemID`', $search)
                ->or_like('s.`Description`', $search)
                ->or_like('s.`bin`', $search)
                ->group_end();
        }
        $columns = ['ID', 'ItemID', 'ItemLookupCode', 'Description'];
        if (isset($columns[$order_by])) {
            $this->db->order_by($columns[$order_by], $order_dir);
        } else {
            $this->db->order_by('s.`ID`', 'asc');
        }
    }

    //
    public function syncstocksheets($search, $order_by, $order_dir, $start, $length, $LookupCode)
    {
        $this->_syncstocksheets($search, $order_by, $order_dir, $LookupCode);
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    // synchronise excel.
    public function syncstocksheets_excel()
    {
        $this->db->select("s.*,a.`Alias`,i.`ItemLookupCode` as ItemCode,i.`Cost`,i.`Price`");
        $this->db->join('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->join('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
        $this->db->where('s.`Status`', 0);
        $this->db->group_by('s.`ID`');
        $this->db->order_by('s.`Quantity`', 'ASC');
        $this->db->order_by('s.`ID`', 'DESC');

        $query = $this->db->get('`stocksheets` s');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Stocktake progress value
    public function syncstocksheetsval()
    {
        $this->db->select("IFNULL(SUM(i.`Cost`*s.`Quantity`),0) as total");
        $this->db->join('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->where('s.`Status`', 0);

        $query = $this->db->get('`stocksheets` s');

        if ($query) {
            if ($query->row() != '') {
                return $query->row()->total;
            } else {
                return 0;
            }
        }
    }

    /** Specific item  */
    public function specific_feed($LookupCode)
    {
        $this->db->select("s.*,a.`Alias`,i.`ItemLookupCode` as ItemCode,i.`Cost`,i.`Price`");
        $this->db->join('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->join('`alias` a', 'a.`ItemID`=i.`ID`', 'LEFT');
        $this->db->where('s.`ItemLookupCode`', $LookupCode);
        $this->db->where('s.`Status`', 0);
        $this->db->order_by('s.`ID`', 'DESC');

        $query = $this->db->get('`stocksheets` s');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Temporary stock sheets report */
    public function tempsheets()
    {
        $this->db->select("t.*");
        $this->db->join('`stocktake` s', 's.`ID`=t.`StocktakeID`');
        $this->db->where('t.`UserID`', $this->session->userdata('ID'));
        $this->db->where('t.`Status`', 0);
        $this->db->where('s.`Status`', 0);
        $this->db->order_by('t.`tTime`', 'DESC');
        $this->db->order_by('t.`ID`', 'DESC');

        $query = $this->db->get('`tempsheets` t');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Selecting reason code */
    public function reasoncode()
    {
        $this->db->select('r.`Description` as reason');
        $this->db->where('r.`Description`', 'Stock Take');
        $query = $this->db->get('`reasoncode` r');

        if ($query) {
            if ($query->row() <> '') {
                return $query->row()->reason;
            } else {
                return 0;
            }
        }
    }

    /** Picking the shelf */
    public function shelf()
    {
        $this->db->select(" (s.`Shelf`)  AS shelf");
        $this->db->join('`stocktake` t', 't.`ID`=s.`StocktakeID`');
        $this->db->where('s.`UserID`', $this->session->userdata('ID'));
        $this->db->where('s.Status', 0);
        $this->db->where('t.`Status`', 0);
        $this->db->order_by('s.`ID`', 'desc');
        $this->db->limit('1');

        $query = $this->db->get('`tempsheets` s');
        if ($query) {
            if ($query->row() <> '') {
                return $query->row()->shelf;
            } else {
                return 0;
            }
        }

        return false;
    }

    /** Stock take history listings */
    public function history()
    {
        $this->db->select("l.`Description`,s.`ID`,s.`Status`,s.`CountingDate`,s.`InitiatedByName`,s.`CommittedName`,s.`DateCommitted`");
        $this->db->join('`inventorylocation` l', 'l.`ID`=s.`StoreID`');
        $this->db->where('s.`Status`!=', 0);
        $this->db->order_by('s.`ID`', 'ASC');

        $query = $this->db->get('`stocktake` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Importing bin numbers */
    public function importData($data)
    {
        $details = $this->db->insert_batch('bins', $data);
        if ($details) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /** Updating user password */
    public function updatepassword()
    {
        $data['Pass'] = $this->input->post('pass');
        $data['LastPasswordChange'] = date('Y-m-d H:i:s');;
        $data['LastUpdated'] = date('Y-m-d H:i:s');

        $query = $this->db->query(" UPDATE `cashier`c SET c. `Pass`= PASSWORD('" . $data['Pass'] . "'),c.`LastPasswordChange`='" . $data['LastPasswordChange'] . "',c.`LastUpdated`='" . $data['LastUpdated'] . "' WHERE c.`ID`='" . $this->session->userdata('ID') . "' ");
    }

    // number of skus in the stock sheets.
    public function details_count($id)
    {
        $this->db->where('s.`StocktakeID`', $id);
        return $this->db->count_all_results('`stocktake_entry` s');
    }

    // counting filtered sheets records.
    public function filtered_details($search, $id)
    {
        $this->_details($search, 0, 'asc', $id);
        return $this->db->count_all_results();
    }

    /** Department listings */
    public function _details($search, $order_by, $order_dir, $id)
    {
        $this->db->select("e.`ItemLookupCode` as lookup,a.`Alias`,e.`Description`,d.`Name` AS department,e.`Cost`,e.`OriginalQty`,e.`CountedQty`");
        $this->db->from('`stocktake_entry` e');
        $this->db->join('`department` d', 'd.`ID`=e.`DepartmentID`', 'LEFT');
        $this->db->join('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
        $this->db->where('e.`StocktakeID`', $id);
        $this->db->where('e.`CountedQty`>', 0);
        $this->db->group_by('e.`ItemID`');
        $this->db->order_by('e.`Description`', 'ASC');

        if (!empty($search)) {
            $this->db->group_start()
                ->like('e.`ID`', $search)
                ->or_like('e.`ItemLookupCode`', $search)
                ->or_like('e.`ItemID`', $search)
                ->or_like('e.`Description`', $search)
                ->or_like('a.`Alias`', $search)
                ->group_end();
        }
        $columns = ['ID', 'ItemID', 'ItemLookupCode', 'Description'];
        if (isset($columns[$order_by])) {
            $this->db->order_by($columns[$order_by], $order_dir);
        } else {
            $this->db->order_by('e.`ID`', 'asc');
        }
    }

    public function details($search, $order_by, $order_dir, $start, $length, $id)
    {
        $this->_details($search, $order_by, $order_dir, $id);
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    // historical stock take excel.
    public function excel_details($id)
    {
        $this->db->select("e.`ItemLookupCode` as lookup,a.`Alias`,e.`Description`,d.`Name` AS department,e.`Cost`,e.`OriginalQty`,e.`CountedQty`");
        $this->db->join('`department` d', 'd.`ID`=e.`DepartmentID`', 'left');
        $this->db->join('`alias` a', 'a.`ItemID`=e.`ItemID`', 'left');
        $this->db->where('e.`StocktakeID`', $id);
        $this->db->where('e.`CountedQty`>', 0);
        $this->db->group_by('e.`ItemID`');
        $this->db->order_by('e.`Description`', 'ASC');
        $query = $this->db->get('`stocktake_entry` e');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    // Getting stock take ID
    public function get_record($id)
    {
        $this->db->where('s.`ID`', $id);
        $query = $this->db->get('`stocktake` s');
        if ($query->num_rows() > 0)
            return $query->result();
    }
}
