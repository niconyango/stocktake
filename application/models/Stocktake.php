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
        $query = $this->db->query("SELECT `ID`,`LastUpdated`,`Number`,`StoreID`,`Name`,Pass,`FloorLimit`,`ReturnLimit`,`DropLimit`,`CashDrawerNumber`,`SecurityLevel`,`Priviledges`,`EmailAddress`,`FailedLogonAttempts`,`MaxOverShortAmount`,`MaxOverShortPercent`,`OverShortLimitType`,`Telephone`,`Enabled`,`TimeSchedule`,`LastPasswordChange`,`PassExpires`,`InventoryLocation`,`SalesRepID`,`BinLocation` FROM `cashier`c  WHERE c.`Number` =  '" . $data['Number'] . "'  AND c.`Pass` = PASSWORD('" . $data['Pass'] . "') ");

        if ($query) {
            return $query->row();   
        }

        return false;
    }
    /** Sheet progress:Dashboard */
    public function sheetprogress()
    {
        $this->db->SELECT('t.`ItemLookupCode`,t.`Itemdescription`,SUM(t.`Quantity`) AS Quantity,SUM(t.`Quantity`*e.`Cost`) AS Value');
        $this->db->JOIN('`stocktake` s', 's.`ID`=t.`StockTakeID`');
        $this->db->JOIN('`item` e', 'e.`ID`=t.`ItemID`');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->GROUP_BY('t.`ItemID`');
        $this->db->ORDER_BY('SUM(t.`Quantity`)', 'DESC');
        $this->db->LIMIT(9);

        $query = $this->db->get('`tempsheets` t');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Departmental progress */
    public function departmentalprogress()
    {
        $this->db->SELECT('d.`Name` AS department,ROUND(SUM(t.`Quantity`*i.`Cost`)) AS value ');

        $this->db->JOIN('`item` i', 'i.`ID`=t.`ItemID`');
        $this->db->JOIN('`department` d', 'd.`ID`=i.`DepartmentID`');
        $this->db->JOIN('`stocktake` s', 's.`ID`=t.`StockTakeID`');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->GROUP_BY('d.`ID`');

        $query = $this->db->get('`tempsheets` t');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /**Existing sytem users */
    public function system_users()
    {
        $this->db->SELECT('c.`ID`,c.`StoreID`,c.`Number`,l.`Description`,c.`Pass`,c.`PassExpires`,c.`Name`,c.`EmailAddress`,c.`Telephone`,c.`Enabled`,c.`SecurityLevel` AS Security,u.`Name` AS SecurityLevel');
        $this->db->JOIN('`usergroup` u', 'u.`ID`=c.`SecurityLevel`', 'LEFT');
        $this->db->JOIN('`inventorylocation` l', 'l.`ID`=c.`StoreID`', 'LEFT');
        $this->db->ORDER_BY('c.`Name`');

        $query = $this->db->get('`cashier` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** User creation method */
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
            $this->db->WHERE('ID', $id);

            $query = $this->db->UPDATE('`cashier`', $data);
            //$this->update_edate($id, $data['SupplierID']);

            return 2;
        } else {
            $query = $this->db->INSERT('`cashier`', $data);

            $id = $this->db->insert_id();
            // $this->update_regdate($id, $data['SupplierID']);

            return 1;
        }
    }
    /**Loading user levels */
    public function userlevels()
    {
        $this->db->SELECT('c.`ID`,c.`Name`');

        $query = $this->db->get('`usergroup` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /**Loding the Branches */
    public function branches()
    {
        $this->db->SELECT('l.`ID`,l.`Description`');

        $query = $this->db->get('`inventorylocation` l');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /**Items listings */
    public function items()
    {
        $this->db->SELECT('i.`ItemLookupCode` as Code,d.`Name` AS Department,c.`Name` AS Category,s.`Name` AS SubCategory,a.`Alias`,i.`Description`,i.`Cost`,i.`quantity`,i.`Price`');
        $this->db->JOIN("department d", "d.`ID`=i.`DepartmentID`", "LEFT");
        $this->db->JOIN("category c", "c.`ID`=i.`CategoryID`", "LEFT");
        $this->db->JOIN("subcategory s", "s.`ID`=i.`SubCategoryID`", "LEFT");
        $this->db->JOIN('alias a', 'a.`ItemID`=i.`ID`', 'LEFT');
        $this->db->GROUP_BY('i.`ID`');
        $this->db->LIMIT('1000');

        $query = $this->db->get('`item` i');
        if ($query->num_rows() > 0)

            return $query->result();
        else
            return false;
    }
    /**List of all the suppliers */
    public function suppliers()
    {
        $this->db->SELECT('s.`ID`,s.`Code`,s.`SupplierName`,s.`ContactName`,s.`City`,s.`Address1`,s.`EmailAddress`,s.`Supplying`,s.`Terms`,s.`PhoneNumber`,s.`TaxNumber`,s.`Address2`,s.`AccountNumber`,s.`Withhold`,s.`TypeofGoods`,s.`Approved`');
        $query = $this->db->get('`supplier` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    /**List of all the suppliers */
    public function customers()
    {
        $this->db->SELECT('c.`ID`,c.`AccountNumber`,c.`Company`,c.`EmailAddress`,c.`TaxNumber`,c.`Address`,c.`State`,c.`Title`,CONCAT(c.`FirstName`," ",c.`LastName`) AS ContactPerson,c.`PhoneNumber`,c.`AccountBalance`,c.`Approved`');

        $query = $this->db->get('`customer` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    /** Stock sheet method */
    public function transactions()
    {
        $this->db->SELECT("i.`ItemLookupCode`,i.`Description`,d.`Name` as department,l.`Quantity`,c.`Name` AS category,s.`Name` as subcategory,i.`Cost`,i.`Price`,i.`UnitOfMeasure`");
        $this->db->JOIN("inventorylocationitems l", "l.`ItemDBID`=i.`ID`");
        $this->db->JOIN("department d", "d.`ID`=i.`DepartmentID`", "LEFT");
        $this->db->JOIN("category c", "c.`ID`=i.`CategoryID`", "LEFT");
        $this->db->JOIN("subcategory s", "s.`ID`=i.`SubCategoryID`", "LEFT");

        $query = $this->db->get('`item` i');
        if ($query->num_rows() > 0)

            return $query->result();
        else
            return false;
    }
    /** Department listings */
    public function users()
    {
        $this->db->ORDER_BY('c.`Name`');

        $query = $this->db->get('`cashier` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Checking available stock takes and their current status */
    public function stocktakestatus()
    {
        $this->db->SELECT("IFNULL(COUNT(s.`ID`),0) as stocktakestatus");
        $this->db->JOIN('`configuration` c', 'c.`StoreID` = s.`StoreID`');
        $this->db->WHERE('s.`Status`', 0);
        /** $this->db->WHERE('s.`Status`',0); */

        $query = $this->db->get('`stocktake` s');
        if ($query) {
            return $query->row()->stocktakestatus;
        }

        return false;
    }
    /** Getting pending sync records */
    public function tempsheets_status()
    {
        $this->db->SELECT("IFNULL(COUNT(s.`ID`),0) AS tempsheets_status");
        $this->db->JOIN('`stocktake` t', 't.`ID`=s.`StocktakeID`');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->WHERE('t.`Status`', 0);

        $query = $this->db->get('`tempsheets` s');
        if ($query) {
            return $query->row()->tempsheets_status;
        }

        return false;
    }
    /** CHecking whether there are records that haven't been synched */
    public function pendingsyncrecords()
    {
        $this->db->SELECT("IFNULL(COUNT(s.`ID`),0) AS pendingsynchrecords");
        $this->db->JOIN('`stocktake` t', 't.`ID`=s.`StocktakeID`');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->WHERE('s.`Synched`', 0);
        $this->db->WHERE('t.`Status`', 0);

        $query = $this->db->get('`stocksheets` s');
        if ($query) {
            return $query->row()->pendingsynchrecords;
        }

        return false;
    }
    /** Stock take progress method */
    public function stocktakeprogress()
    {
        $this->db->SELECT("IFNULL(COUNT(t.`ItemID`),0) as stocktakeprogress");
        $this->db->JOIN('`configuration` c', 'c.`StoreID` = s.`StoreID`');
        $this->db->JOIN('`tempsheets` t', 't.`StocktakeID`=s.`ID`');
        $this->db->WHERE('s.`Status`', 0);
        /** $this->db->WHERE('s.`Status`',0); */

        $query = $this->db->get('`stocktake` s');
        if ($query) {
            return $query->row()->stocktakeprogress;
        }

        return false;
    }
    /** Stock take progress method */
    public function stocktakesheets()
    {
        $this->db->SELECT("IFNULL(COUNT(t.`ItemID`),0) as stocktakesheets");
        $this->db->JOIN('`configuration` c', 'c.`StoreID` = s.`StoreID`');
        $this->db->JOIN('`stocksheets` t', 't.`StocktakeID`=s.`ID`');
        $this->db->WHERE('s.`Status`', 0);

        $query = $this->db->get('`stocktake` s');
        if ($query) {
            return $query->row()->stocktakesheets;
        }

        return false;
    }
    /** Department listings */
    public function departments()
    {
        $this->db->SELECT('d.`ID`,d.`Name`,d.`Code`,d.`pMargin`,d.`pComm`');
        $this->db->ORDER_BY('d.`Name`');

        $query = $this->db->get('`department` d');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Category listings */
    public function category()
    {
        $this->db->SELECT('c.`ID`,c.`Name` as category');
        $this->db->ORDER_BY('c.`Name`');

        $query = $this->db->get('`category` c');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Sub Category listings */
    public function subcategory()
    {
        $this->db->SELECT('s.`ID`,s.`Name` AS subcategory');
        $this->db->ORDER_BY('s.`Name`');

        $query = $this->db->get('`subcategory` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Category listings */
    public function get_categories_department($id)
    {
        $this->db->WHERE('`DepartmentID`', $id);
        $this->db->ORDER_BY('Name');

        $query = $this->db->get('`category`');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Category listings */
    public function get_subcategories_department($id)
    {
        $this->db->WHERE('`CategoryID`', $id);
        $this->db->ORDER_BY('Name');

        $query = $this->db->get('`subcategory`');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Inventory location name */
    public function storeconfig()
    {
        $this->db->SELECT("l.`Description` AS storename");
        $this->db->JOIN('`inventorylocation` l', 'l.`ID` = s.`StoreID`');

        $query = $this->db->get('`configuration` s');
        if ($query) {
            return $query->row()->storename;
        }

        return false;
    }
    /** Freezing stocks for stock take method */
    public function fstocks()
    {
        /** Checking whether an item is missing in inventorylocationitems */
        $query0 = $this->db->query("INSERT `inventorylocationitems`(`InventoryLocation`,`ItemDBID`,`Quantity`) SELECT (SELECT c.`StoreID` FROM `configuration`c) AS InventoryLocation,i.`ID` AS ItemDBID,i.`quantity` AS Quantity FROM `item`i WHERE i.`ID` NOT IN (SELECT ItemDBID FROM `inventorylocationitems`l JOIN `configuration`c ON c.`StoreID`=l.`InventoryLocation`)");
        /** Creating stock take header */
        $query = $this->db->query("INSERT `stocktake`(`StoreID`,`CountingDate`,`InitiatedByID`,`InitiatedByName`,`Lastupdated`) SELECT c.`StoreID`,NOW() AS CountingDate,'" . $this->session->userdata('ID') . "','" . $this->session->userdata('Name') . "',NOW() AS Lastupdated FROM `configuration`c ");

        $stocktakeid = $this->db->insert_id();
        /** Inserting all SKUs records in stocktake_entry table */
        $query1 = $this->db->query("INSERT INTO `stocktake_entry`(`StocktakeID`,`StoreID`,`DepartmentID`,`CategoryID`,`SubCategoryID`,`ItemID`,`ItemLookupCode`,`Description`,`BinLocation`,`tDate`,`OriginalQty`,`Lastupdated`,`Cost`,`Price`) SELECT '$stocktakeid',c.`StoreID`,IFNULL(i.`DepartmentID`,0),IFNULL(i.`CategoryID`,0),IFNULL(i.`SubCategoryID`,0),i.`ID`,i.`ItemLookupCode`,i.`Description`,`BinLocation`,NOW(),l.`Quantity`,NOW() AS Lastupdated,i.`Cost`,i.`Price` FROM `item`i JOIN `inventorylocationitems`l ON l.`ItemDBID`=i.`ID` JOIN `configuration`c ON c.`StoreID` = l.`InventoryLocation` ");
        if ($query1) {
            return true;
        }

        return false;
    }
    /** Showing the stock sheet that haven't been saved */
    public function pending_tempsheets()
    {
        $this->db->SELECT("t.`StocktakeID` AS StocktakeID,t.`UserID`,t.`Shelf` AS bin,t.`Quantity`,e.`Cost`,e.`Price`,SUM(t.`Quantity`*e.`Cost`) AS costvalue,t.`CashierName` AS user,SUM(t.`Quantity`*e.`Price`) AS pricevalue");
        $this->db->JOIN('`stocktake` s', 's.`ID`=t.`StocktakeID`');
        $this->db->JOIN('`stocktake_entry` e', 'e.`ItemID`=t.`ItemID`');
        $this->db->WHERE('t.`Status`', 0);
        $this->db->WHERE('e.`StocktakeID`=t.`StocktakeID`');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->GROUP_BY('t.`CashierName`');

        $query = $this->db->get('`tempsheets` t');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Posting stocks process */
    public function post_stocks()
    {
        // Updating uncounted SKUs
        $queryx = $this->db->query("UPDATE `stocktake_entry`e  SET e.`QtyDiff`=IF(e.`OriginalQty`= 0,0,IFNULL(e.`CountedQty`-e.`OriginalQty`,0)), e.`Lastupdated`='" . date('Y-m-d h:i:s') . "' WHERE e.`Status`= 0 AND e.`CountedDate` IS NULL ");

        /** Updating stock take in physicalinventoryentry table */
        $query4 = $this->db->query("INSERT `physicalinventory`(`InventoryLocation`,`StoreID`,`OpenTime`,`CloseTime`,`Status`,`LastRefresh`,`Description`,`uUser`) SELECT s.`StoreID`,s.`StoreID`,s.`CountingDate`,NOW() AS CloseTime,2 AS Status,NOW() AS LastRefresh,'Stock Take' AS Description,'" . $this->session->userdata('Name') . "' FROM `stocktake`s WHERE s.`Status`=0");

        $physical = $this->db->insert_id();

        $query0 = $this->db->query("SELECT s.`ID` as StockTakeID FROM  `stocktake`s WHERE s.`Status`=0 ");
        $StockTakeID = $query0->row()->StockTakeID;

        /** Updating stock take in physicalinventoryentry table */
        $query5 = $this->db->query("INSERT `physicalinventoryentry`(`StoreID`,`PhysicalInventoryID`,`ReasonCodeID`,`CountTime`,`ItemID`,`BinLocation`,`Price`,`Cost`,`QuantityCounted`,`QuantityAdjusted`,`QuantityRefreshed`) SELECT e.`StoreID`,'$physical',97 AS ReasonCodeID,`CountedDate`,e.`ItemID`,e.`BinLocation`,e.`Price`,e.`Cost`,e.`CountedQty` AS QuantityCounted,(e.`QtyDiff`) AS QuantityAdjusted,e.`CountedQty` AS QuantityRefreshed FROM `stocktake_entry`e WHERE e.`Status`= 0 AND e.`StocktakeID`='$StockTakeID'
        AND e.`QtyDiff`<>0 ");

        /** Updating stock quantities in the item table */
        $query6 = $this->db->query("UPDATE `item`i JOIN `stocktake_entry`e  ON e.`ItemID`=i.`ID` SET i.`quantity`= (i.`quantity`+e.`QtyDiff`) WHERE e.`Status`=0 AND e.`CountedDate` IS NOT NULL AND e.`CountedQty`<>0 AND e.`StocktakeID`=$StockTakeID");

        /** Updating items that were not counted */
        $query7 = $this->db->query("UPDATE `item`i JOIN `stocktake_entry`e  ON e.`ItemID`=i.`ID` SET i.`quantity`= e.`CountedQty` WHERE e.`Status`=0 AND e.`CountedDate` IS NULL AND e.`CountedQty`=0 AND e.`StocktakeID`='$StockTakeID'");

        /** Updating stock quantities in the inventorylocation item table */
        $query8 = $this->db->query("UPDATE `inventorylocationitems`l JOIN `item`i ON i.`ID`=l.`ItemDBID` SET l.`Quantity`= i.`quantity` WHERE l.`InventoryLocation` IN (SELECT c.`StoreID` FROM `configuration`c) AND i.`ID`=l.`ItemDBID`");

        /** Closing the stock take */
        $query9 = $this->db->query("UPDATE `stocktake`s SET s.`Status`=1,s.`Committed`='" . $this->session->userdata('ID') . "',s.`CommittedName`='" . $this->session->userdata('Name') . "',s.`Lastupdated`=NOW(),s.`DateCommitted`=NOW()");

        /** Closing the stock take details */
        $query10 = $this->db->query("UPDATE `stocktake_entry`e SET e.`Status` = 1,e.`Lastupdated`= NOW(),e.`CountedDate` = NOW()");

        /** Updating  stock take sheets with closed status */
        $query11 = $this->db->query("UPDATE `stocksheets`s SET s.`Status` = 1,s.`cDate`= NOW()");

        /** Updating the right physicalinventory code */
        $query12 = $this->db->query("UPDATE `physicalinventory`p SET p.`Code`=CASE WHEN LENGTH($physical)=1 THEN CONCAT('00000','',$physical)  WHEN LENGTH($physical)=2 THEN CONCAT('0000','',$physical)  WHEN LENGTH($physical)=3 THEN CONCAT('000','',$physical) WHEN LENGTH($physical)=4 THEN CONCAT('00','',$physical) WHEN LENGTH($physical)=5 THEN CONCAT('0','',$physical) ELSE $physical END");


        if ($query11) {
            return true;
        }

        return false;
    }

    /** Posting stocks process */
    public function post_counted()
    {
        /** Updating stock take in physicalinventoryentry table */
        $query4 = $this->db->query("INSERT `physicalinventory`(`InventoryLocation`,`StoreID`,`OpenTime`,`CloseTime`,`Status`,`LastRefresh`,`Description`,`uUser`) SELECT s.`StoreID`,s.`StoreID`,s.`CountingDate`,NOW() AS CloseTime,2 AS Status,NOW() AS LastRefresh,'Stock Take' AS Description,'" . $this->session->userdata('Name') . "' FROM `stocktake`s WHERE s.`Status`=0");

        $physical = $this->db->insert_id();

        /** Picking the stock take record */
        $query0 = $this->db->query("SELECT s.`ID` as StockTakeID FROM  `stocktake`s WHERE s.`Status`=0 ");
        $StockTakeID = $query0->row()->StockTakeID;

        /** Updating stock take in physicalinventoryentry table */
        $query5 = $this->db->query("INSERT `physicalinventoryentry`(`StoreID`,`PhysicalInventoryID`,`ReasonCodeID`,`CountTime`,`ItemID`,`BinLocation`,`Price`,`Cost`,`QuantityCounted`,`QuantityAdjusted`,`QuantityRefreshed`) SELECT e.`StoreID`,'$physical',97 AS ReasonCodeID,`CountedDate`,e.`ItemID`,e.`BinLocation`,e.`Price`,e.`Cost`,e.`CountedQty` AS QuantityCounted,(e.`QtyDiff`) AS QuantityAdjusted,e.`CountedQty` AS QuantityRefreshed FROM `stocktake_entry`e WHERE e.`Status`= 0 AND e.`StocktakeID`='$StockTakeID' AND e.`QtyDiff`<>0 AND e.`CountedDate` IS NOT NULL");

        /** Updating stock quantities in the item table */
        $query6 = $this->db->query("UPDATE `item`i JOIN `stocktake_entry`e  ON e.`ItemID`=i.`ID` SET i.`quantity`= (i.`quantity`+e.`QtyDiff`) WHERE e.`Status`=0 AND e.`CountedDate` IS NOT NULL AND e.`StocktakeID`=$StockTakeID");

        /** Updating stock quantities in the inventorylocation item table */
        $query8 = $this->db->query("UPDATE `inventorylocationitems`l JOIN `item`i ON i.`ID`=l.`ItemDBID` SET l.`Quantity`= i.`quantity` WHERE l.`InventoryLocation` IN (SELECT c.`StoreID` FROM `configuration`c) AND i.`ID`=l.`ItemDBID`");

        /** Closing the stock take */
        $query9 = $this->db->query("UPDATE `stocktake`s SET s.`Status`=1,s.`Committed`='" . $this->session->userdata('ID') . "',s.`CommittedName`='" . $this->session->userdata('Name') . "',s.`Lastupdated`=NOW(),s.`DateCommitted`=NOW() WHERE s.`ID`=$StockTakeID");

        /** Closing the stock take details */
        $query10 = $this->db->query("UPDATE `stocktake_entry`e SET e.`Status` = 1,e.`Lastupdated`= NOW(),e.`CountedDate` = NOW() WHERE e.`StocktakeID`=$StockTakeID");

        /** Updating  stock take sheets with closed status */
        $query11 = $this->db->query("UPDATE `stocksheets`s SET s.`Status` = 1,s.`cDate`= NOW() WHERE s.`StocktakeID`=$StockTakeID");

        /** Updating the right physicalinventory code */
        $query12 = $this->db->query("UPDATE `physicalinventory`p SET p.`Code`=CASE WHEN LENGTH($physical)=1 THEN CONCAT('00000','',$physical) WHEN LENGTH($physical)=2 THEN CONCAT('0000','',$physical) WHEN LENGTH($physical)=3 THEN CONCAT('000','',$physical) WHEN LENGTH($physical)=4 THEN CONCAT('00','',$physical)  WHEN LENGTH($physical)=5 THEN CONCAT('0','',$physical) ELSE $physical END");

        if ($query11) {
            return true;
        }

        return false;
    }

    /** Specific sheet details */
    public function product($id)
    {
        $this->db->SELECT('`ItemID`,`ItemLookupCode`,`Quantity`,`Description`,DATE(`CountedDate`) AS tDate,`Username`,`bin`');
        $this->db->FROM('stocksheets');
        $this->db->WHERE('ItemID', $id);
        $this->db->WHERE('Status', 0);

        $query = $this->db->get();

        return $query->result();
    }

    /** Pending sheet(sheets that have not been saved grouped by respective users) */
    public function products($id)
    {
        $this->db->SELECT('`ItemID`,`ItemLookupCode`,`Quantity`,`Itemdescription` AS Description,DATE(`tTime`) AS tDate,`CashierName` AS Username,`Shelf` as bin');
        $this->db->FROM('`tempsheets`');
        $this->db->WHERE('UserID', $id);
        $this->db->WHERE('Status', 0);
        $this->db->GROUP_BY('ID');

        $query = $this->db->get();

        return $query->result();
    }

    /** Getting fed item description */
    public function code_desc($id)
    {
        $query = $this->db->query("SELECT i.`Description` FROM  `item`i  WHERE i.`ItemLookupCode`= $id UNION SELECT i.`Description` FROM `alias` a JOIN `item`i ON i.`ID`=a.`ItemID` WHERE a.`Alias`= $id ");

        return $query->row();
    }

    // Getting the counted SKUs records.
    public function stocks()
    {
        $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

        $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
        $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
        $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
        $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
        $this->db->GROUP_BY('s.`ID`');
        $this->db->WHERE('s.`CountedQty`>', 0);
        $this->db->WHERE('s.Status', 0);
        // $this->db->LIMIT('100');

        $query = $this->db->get('stocktake_entry s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    // Uncounted SKUs.
    public function uncounted()
    {
        $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

        $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
        $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
        $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
        $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
        $this->db->WHERE('s.`CountedQty`', 0);
        $this->db->WHERE('s.Status', 0);
        $this->db->LIMIT('1000');

        $query = $this->db->get('`stocktake_entry` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    // Case 1: Where a user is quering specifics(Department,category & subcategory).
    public function counted_stocks($DepartmentID, $CategoryID, $SubCategoryID)
    {
        if (($DepartmentID != 0) && ($CategoryID != 0) && ($SubCategoryID != 0)) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->WHERE('d.`ID`', $DepartmentID);
            $this->db->WHERE('c.`ID`', $CategoryID);
            $this->db->WHERE('e.`ID`', $SubCategoryID);
            $this->db->WHERE('s.`CountedQty`>', 0);
            $this->db->WHERE('s.Status', 0);

            $query = $this->db->get('stocktake_entry s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
        // Case 2: Where the user isn't specific on any subcategory.
        elseif (($DepartmentID != 0) && ($CategoryID != 0) && ($SubCategoryID == 0)) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->WHERE('d.`ID`', $DepartmentID);
            $this->db->WHERE('c.`ID`', $CategoryID);
            $this->db->WHERE('s.`CountedQty`>', 0);
            $this->db->WHERE('s.Status', 0);

            $query = $this->db->get('stocktake_entry s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }

        /** Case 3: Where a user is quering a specific Department */
        elseif (($DepartmentID != 0) && ($CategoryID == 0) && ($SubCategoryID == 0)) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->WHERE('d.`ID`', $DepartmentID);
            $this->db->WHERE('s.`CountedQty`>', 0);
            $this->db->WHERE('s.Status', 0);

            $query = $this->db->get('stocktake_entry s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }

        /** Case 4: Where a user is quering all stocks(:items) */
        elseif (($DepartmentID == 0) && ($CategoryID == 0) && ($SubCategoryID == 0)) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,s.Cost AS Cost,s.`OriginalQty` AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->WHERE('s.`CountedQty`>', 0);
            $this->db->WHERE('s.Status', 0);

            $query = $this->db->get('stocktake_entry s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
    }
    /** Uncounted stocks category report */
    public function uncounted_stocks($DepartmentID, $CategoryID, $SubCategoryID)
    {
        if ($DepartmentID != 0 && $CategoryID != 0 && $SubCategoryID != 0) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
            $this->db->WHERE('s.`DepartmentID`', $DepartmentID);
            $this->db->WHERE('s.`CategoryID`', $CategoryID);
            $this->db->WHERE('s.`SubCategoryID`', $SubCategoryID);
            $this->db->WHERE('s.`CountedQty`', 0);
            $this->db->WHERE('s.Status', 0);
            $this->db->GROUP_BY('s.`ItemID`');

            $query = $this->db->get('`stocktake_entry` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        } elseif ($DepartmentID != 0 && $CategoryID != 0 && $SubCategoryID == 0) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
            $this->db->WHERE('s.`DepartmentID`', $DepartmentID);
            $this->db->WHERE('s.`CategoryID`', $CategoryID);
            $this->db->WHERE('s.`CountedQty`', 0);
            $this->db->WHERE('s.Status', 0);
            $this->db->GROUP_BY('s.`ItemID`');

            $query = $this->db->get('`stocktake_entry` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        } elseif ($DepartmentID != 0 && $CategoryID == 0 && $SubCategoryID == 0) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
            $this->db->WHERE('s.`DepartmentID`', $DepartmentID);
            $this->db->WHERE('s.`CountedQty`', 0);
            $this->db->WHERE('s.Status', 0);
            $this->db->GROUP_BY('s.`ItemID`');

            $query = $this->db->get('`stocktake_entry` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        } elseif ($DepartmentID == 0 && $CategoryID == 0 && $SubCategoryID == 0) {
            $this->db->SELECT('d.`Name` AS department,c.`Name` AS category,e.`Name` AS subcategory,s.`ItemID` AS ItemID,s.ItemLookupCode AS Code,s.`Description` AS description,IFNULL(s.Cost,0) AS Cost,IFNULL(s.`Price`,0) AS Price,IFNULL(s.`OriginalQty`,0) AS OriginalQty,s.`CountedQty` AS CountedQty,DATE(s.`CountedDate`) AS CountedDate,a.`Alias`');

            $this->db->JOIN('department d', 'd.`ID`=s.`DepartmentID`', 'LEFT');
            $this->db->JOIN('category c', 'c.`ID`=s.`CategoryID`', 'LEFT');
            $this->db->JOIN('subcategory e', 'e.`ID`=s.`SubCategoryID`', 'LEFT');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
            $this->db->WHERE('s.`CountedQty`', 0);
            $this->db->WHERE('s.Status', 0);
            $this->db->GROUP_BY('s.`ItemID`');


            $query = $this->db->get('`stocktake_entry` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
    }
    /** Current stock list */
    public function holdings()
    {
        $this->db->SELECT('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` as itemcode,e.`Description`,e.`BinLocation` as bin,e.`Price`,e.`Cost`,e.`OriginalQty`,DATE(s.`CountingDate`) as CountingDate,e.`CountedQty`');
        $this->db->JOIN('`stocktake_entry` e', 's.`ID` = e.`StocktakeID`');
        $this->db->JOIN('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->WHERE('e.`OriginalQty`>=', 0);
        $this->db->GROUP_BY('e.`ItemID`');
        $this->db->ORDER_BY('e.`ItemID`', 'DESC');
        $this->db->LIMIT('100');

        $query = $this->db->get('`stocktake` s');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }

    /** Current stocks standing */
    public function customised_holdings($DepartmentID, $CategoryID, $SubCategoryID)
    {
        /** Case 1: Where a user is quering specifics(Department,category & subcategory) */
        if ($DepartmentID != 0 && $CategoryID != 0 && $SubCategoryID != 0) {
            $this->db->SELECT('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` as itemcode,e.`Description`,e.`BinLocation` as bin,IFNULL(e.`Price`,0) AS Price,IFNULL(e.`Cost`,0) AS Cost,IFNULL(e.`OriginalQty`,0) AS OriginalQty,DATE(s.`CountingDate`) as CountingDate,e.`CountedQty`');
            $this->db->JOIN('`stocktake_entry` e', 's.`ID` = e.`StocktakeID`');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
            $this->db->WHERE('e.`DepartmentID`', $DepartmentID);
            $this->db->WHERE('e.`CategoryID`', $CategoryID);
            $this->db->WHERE('e.`SubCategoryID`', $SubCategoryID);
            //$this->db->WHERE('e.`OriginalQty`>=', 0);
            $this->db->WHERE('s.`Status`', 0);
            $this->db->GROUP_BY('e.`ItemID`');
            $this->db->ORDER_BY('e.`ItemID`', 'DESC');

            $query = $this->db->get('`stocktake` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
        /** Case 2: Where the user isn't specific on any category */
        elseif ($DepartmentID != 0 && $CategoryID != 0 && $SubCategoryID == 0) {
            $this->db->SELECT('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` as itemcode,e.`Description`,e.`BinLocation` as bin,IFNULL(e.`Price`,0) AS Price,IFNULL(e.`Cost`,0) AS Cost,IFNULL(e.`OriginalQty`,0) AS OriginalQty,DATE(s.`CountingDate`) as CountingDate,e.`CountedQty`');
            $this->db->JOIN('`stocktake_entry` e', 's.`ID` = e.`StocktakeID`');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
            $this->db->WHERE('e.`DepartmentID`', $DepartmentID);
            $this->db->WHERE('e.`CategoryID`', $CategoryID);
            //$this->db->WHERE('e.`OriginalQty`>=', 0);
            $this->db->WHERE('s.`Status`', 0);
            $this->db->GROUP_BY('e.`ItemID`');
            $this->db->ORDER_BY('e.`ItemID`', 'DESC');

            $query = $this->db->get('`stocktake` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
        /** Case 3: Where a user has provided only the Department */
        elseif ($DepartmentID != 0 && $CategoryID == 0 && $SubCategoryID == 0) {
            $this->db->SELECT('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` as itemcode,e.`Description`,e.`BinLocation` as bin,IFNULL(e.`Price`,0) AS Price,IFNULL(e.`Cost`,0) AS Cost,IFNULL(e.`OriginalQty`,0) AS OriginalQty,DATE(s.`CountingDate`) AS CountingDate,e.`CountedQty`');
            $this->db->JOIN('`stocktake_entry` e', 's.`ID` = e.`StocktakeID`');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
            $this->db->WHERE('e.`DepartmentID`', $DepartmentID);
            //$this->db->WHERE('e.`OriginalQty`>=', 0);
            $this->db->WHERE('s.`Status`', 0);
            $this->db->GROUP_BY('e.`ItemID`');
            $this->db->ORDER_BY('e.`ItemID`', 'DESC');

            $query = $this->db->get('`stocktake` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
        /** Case 4: Current stock list */
        elseif ($DepartmentID == 0 && $CategoryID == 0 && $SubCategoryID == 0) {
            $this->db->SELECT('e.`ItemID`,a.`Alias`,e.`ItemLookupCode` AS itemcode,e.`Description`,e.`BinLocation` AS bin,IFNULL(e.`Price`,0) AS Price,IFNULL(e.`Cost`,0) AS Cost,IFNULL(e.`OriginalQty`,0) AS OriginalQty,DATE(s.`CountingDate`) AS CountingDate,e.`CountedQty`');
            $this->db->JOIN('`stocktake_entry` e', 's.`ID` = e.`StocktakeID`');
            $this->db->JOIN('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
            //$this->db->WHERE('e.`OriginalQty`>=', 0);
            $this->db->WHERE('s.`Status`', 0);
            $this->db->GROUP_BY('e.`ItemID`');
            $this->db->ORDER_BY('e.`ItemID`', 'DESC');

            $query = $this->db->get('`stocktake` s');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else
                return false;
        }
    }
    /**  */
    public function historysearch($user, $startdate, $enddate)
    {
        $this->db->SELECT("l.`Description`,s.`ID`,s.`Status`,s.`CountingDate`,s.`InitiatedByName`,s.`CommittedName`,s.`DateCommitted`");
        $this->db->JOIN('`inventorylocation` l', 'l.`ID`=s.`StoreID`');
        /** $this->db->WHERE('s.`Status`<>', 0); */
        $this->db->WHERE('s.`Committed`', $user);
        $this->db->WHERE('DATE(s.`DateCommitted`)>=', $startdate);
        $this->db->WHERE('DATE(s.`DateCommitted`)<=', $enddate);
        $this->db->ORDER_BY('s.`ID`', 'ASC');

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
        $queryx = $this->db->query("SELECT COUNT(t.`ID`) AS left_entries FROM `tempsheets` t JOIN `stocktake` s ON s.`ID`=t.`StocktakeID` WHERE t.`Status`=0 AND s.`Status`=0");

        $left_entries =  $queryx->row()->left_entries;

        if ($left_entries == 0) {
            /** Getting the active stocktake ID */
            $query = $this->db->query("SELECT s.`ID` AS stocktakeid,s.`CountingDate` AS CountingDate FROM `stocktake`s WHERE s.`Status`=0");
            /** Picking ID to be used */
            foreach ($query->result_array() as $row) {
                $stocktakeid = $row['stocktakeid'];
                $CountingDate = $row['CountingDate'];
            }
            /** $stocktakeid = $query0->row()->stocktakeid;*/

            /**Deleting the stocktake_entry records before synching */
            $query0 = $this->db->query("DELETE e FROM  `stocktake_entry`e WHERE e.`StocktakeID`= $stocktakeid");

            /** Inserting all SKUs records in stocktake_entry table: (This is to take care of all the SKUs that may have been left out during the initial stock freeze) */
            $query1 = $this->db->query("INSERT INTO `stocktake_entry`(`StocktakeID`,`StoreID`,`DepartmentID`,`CategoryID`,`SubCategoryID`,`ItemID`,`ItemLookupCode`,`Description`,`BinLocation`,`tDate`,`OriginalQty`,`Lastupdated`,`Cost`,`Price`) SELECT '$stocktakeid',c.`StoreID`,IFNULL(i.`DepartmentID`,0),IFNULL(i.`CategoryID`,0),IFNULL(i.`SubCategoryID`,0),i.`ID`,i.`ItemLookupCode`,i.`Description`,`BinLocation`,'$CountingDate',l.`Quantity`,NOW() AS Lastupdated,i.`Cost`,i.`Price` FROM `item`i JOIN `inventorylocationitems`l ON l.`ItemDBID`=i.`ID` JOIN `configuration`c ON c.`StoreID` = l.`InventoryLocation` ");

            /** Clearing stockshets_complete before synching the sheets */
            $query2 = $this->db->query("TRUNCATE TABLE `stockshets_complete`");

            /** Pushing stocktake records to a temporary database */
            $query3 = $this->db->query("INSERT `stockshets_complete`(`StocktakeID`,`ItemID`,`Quantity`,`Status`) SELECT e.`StocktakeID`,e.`ItemID`,IFNULL(SUM(e.`Quantity`),0) AS Quantity,e.`Status` FROM `stocksheets`e WHERE  e.`Status`=0 GROUP BY e.`ItemID` ORDER BY e.`ItemID`;");

            /** Updating counts to the stocktake_entry table */
            $query4 = $this->db->query("UPDATE `stocktake_entry`s JOIN  `stockshets_complete`c ON c.`ItemID`=s.`ItemID` SET s.`CountedQty`=c.`Quantity`,s.`Lastupdated`='" . date('Y-m-d h:i:s') . "',s.`CountedDate`='" . date('Y-m-d h:i:s') . "' WHERE s.`Status`= 0 AND c.`ItemID`=s.`ItemID` AND s.`ItemID`IN (SELECT s.`ItemID` FROM `stocksheets`s)");

            $query5 = $this->db->query("UPDATE `stocktake_entry`s  SET s.`QtyDiff`=IFNULL(s.`CountedQty`-s.`OriginalQty`,0),s.`Lastupdated`='" . date('Y-m-d h:i:s') . "' WHERE s.`Status`= 0 AND s.`CountedDate` IS NOT NULL");

            $query6 = $this->db->query("UPDATE `stocksheets` s SET s.`Synched`=1 WHERE s.`StocktakeID`=$stocktakeid");

            if ($query1) {
                return true;
            }

            return false;
        } else {
            return 9;
        }
    }
    /** Un-doing stock take that has been initiated */
    public function undofreeze()
    {
        $query0 = $this->db->query("SELECT ID AS stocktakeid FROM `stocktake`s WHERE s.`Status`=0");
        /** Picking ID to be deleted(:to undo) */
        $stocktakeid = $query0->row()->stocktakeid;
        $query = $this->db->query("DELETE s FROM  `stocktake`s WHERE s.`ID`='$stocktakeid'");

        /** Deleting stocktake entry */
        $querye = $this->db->query("SELECT MIN(e.`ID`) AS min_entry FROM `stocktake_entry`e WHERE e.`StocktakeID`='$stocktakeid'");
        $min_entry = $querye->row()->min_entry;
        $query1 = $this->db->query("DELETE e FROM `stocktake_entry`e WHERE e.`Status`= 0 AND e.`StocktakeID`='$stocktakeid' ");
        /** Deleting sheets */
        $query2 = $this->db->query("DELETE s FROM `stocksheets`s WHERE s.`StocktakeID`='$stocktakeid' ");
        /** Deleting temporary sheets */
        $query3 = $this->db->query("DELETE t FROM `tempsheets`t WHERE t.`StocktakeID`='$stocktakeid' ");

        /** Reseting stocktake table auto_increnment ID */
        $query4 = $this->db->query("ALTER TABLE `stocktake` AUTO_INCREMENT = $stocktakeid");
        $query5 = $this->db->query("OPTIMIZE TABLE `stocktake`");

        /** Reseting stocktake_entry auto_increment ID */
        $query6 = $this->db->query("ALTER TABLE `stocktake_entry` AUTO_INCREMENT = $min_entry");
        $quer7 = $this->db->query("OPTIMIZE TABLE `stocktake_entry`");

        if ($query3) {
            return true;
        }

        return false;
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

        if (
            $id > 0
        ) {
            $this->db->WHERE('ID', $id);
            $query = $this->db->UPDATE('stocksheets', $data);
            return 1;
        } else {
            return 2;
        }
    }
    /** Deleting stocksheet entry */
    public function del_sheet_entry($id)
    {
        $query = $this->db->query("DELETE t FROM `stocksheets`t WHERE t.`ID`='$id' ");

        if ($query) {
            return true;
        }
        return false;
    }

    /** Feeding stock take data */
    public function stock_take()
    {
        $id = $this->input->post('action');

        $data['ItemLookupCode'] = $this->input->post('item_code');
        $data['Quantity'] = $this->input->post('quantity');
        $data['tTime'] = date('Y-m-d H:i:s');
        $data['UserID'] = $this->session->userdata('ID');
        $data['CashierName'] = ucwords($this->session->userdata('Name'));
        $data['Shelf'] = strtoupper($this->input->post('bin'));
        $data['Reasoncode'] = $this->input->post('reasoncode');

        /** $query = $this->db->get_where('item', array('ItemLookupCode' => $data['ItemLookupCode'])); */

        /** Checking the existence of the code in the item master list */
        $query = $this->db->query("SELECT i.`ID` FROM  `item`i  WHERE i.`ItemLookupCode`= '" . $data['ItemLookupCode'] . "' UNION SELECT a.`ItemID` FROM `alias` a WHERE a.`Alias`= '" . $data['ItemLookupCode'] . "' ");

        if ($query->num_rows() == 0) {
            /** If the code entered is missing in the master list */
            return 3;
        } else {
            if ($id > 0) {
                $this->db->WHERE('ID', $id);

                $query = $this->db->UPDATE('tempsheets', $data);

                $updated = $this->db->affected_rows();
                if ($updated)
                    $this->tempsheet_update($id, $data['ItemLookupCode']);

                return 2;
            } else {
                /** Checking whether the bin/shelf is captured */
                if ($data['Shelf'] == 'NULL' || $data['Shelf'] == '0') {
                    /** If the bin/shelf field is left blank */
                    return 8;
                } else {
                    /** Checking the sheet entiers' count */
                    $queryxx = $this->db->query("SELECT COUNT(s.`ID`) as entries FROM  `tempsheets`s JOIN `stocktake`t ON t.`ID`=s.`StocktakeID` WHERE s.`UserID`='" . $this->session->userdata('ID') . "' AND s.`CashierName` = '" . $this->session->userdata('Name') . "' AND s.`Status`=0 AND t.`Status`=0 ");

                    $entries = $queryxx->row()->entries;

                    if ($entries >= 15) {
                        /** If maximum sheeet entries is reached */
                        return 9;
                    } else {
                        /** If the sheet entries isn't reached */
                        $user =  $this->session->userdata('Name');

                        $query1 = $this->db->get_where('tempsheets', array('ItemLookupCode' => $data['ItemLookupCode'], 'Shelf' => $data['Shelf'], 'Status' => '0', 'CashierName' => $user));

                        if ($query1->num_rows() == 0) {
                            /** If the code isn't repeated in the pending sheet */
                            $query12 = $this->db->query("SELECT s.`Shelf` AS shelfname FROM  `tempsheets`s JOIN `stocktake`t ON t.`ID`=s.`StocktakeID` WHERE s.`Status`=0 AND t.`Status`=0 AND s.`CashierName` = '$user'");
                            $shelfname = $query12->row()->shelfname;

                            if ($shelfname == $data['Shelf'] || $shelfname == NULL) {

                                $query = $this->db->INSERT('tempsheets', $data);
                                $id = $this->db->insert_id();
                                $this->update_stocksheets($id, $data['ItemLookupCode']);

                                return 1;
                            } else {
                                return 5;
                            }
                        } else {
                            /** Keeping record of repeated item code */
                            $query = $this->db->query("INSERT  `tempduplicate`(`ItemLookupCode`,`Quantity`,`tTime`,`UserID`,`CashierName`) VALUES('" . $data['ItemLookupCode'] . "', '" . $data['Quantity'] . "',NOW(),'" . $data['UserID'] . "','" . $data['CashierName'] . "')");

                            return 4;
                        }
                    }
                }
            }
        }
    }
    /** Updating the existing SKU with the found quantities */
    public function updatecode()
    {
        $query = $this->db->query("UPDATE `tempsheets`s JOIN `tempduplicate`d ON d.`ItemLookupCode`=s.`ItemLookupCode` SET s.`Quantity` = (s.`Quantity`+d.`Quantity`),s.`tTime` = NOW() WHERE s.`UserID` = d.`UserID` AND s.`CashierName`=d.`CashierName` AND s.`Status`=0");

        $query2 = $this->db->query("DELETE d FROM `tempduplicate`d WHERE d.`UserID`='" . $this->session->userdata('ID') . "' AND d.`CashierName`='" . $this->session->userdata('Name') . "' ");

        if ($query) {
            return 6;
        } else {
            return 2;
        }
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
        /** When the user keys in ItemLookupCode */
        $query = $this->db->query("UPDATE `tempsheets`s JOIN `item`i ON s.`ItemLookupCode`=i.`ItemLookupCode` SET s.`ItemID`=i.`ID`,s.`Itemdescription`=i.`Description` WHERE s.`ItemLookupCode`= '$ItemLookupCode' AND s.`ID`=$id");

        /** When the user keys in an Alias code */
        $query0 = $this->db->query("UPDATE `tempsheets`s JOIN `alias`a ON s.`ItemLookupCode`=a.`Alias` JOIN `item`i ON a.`ItemID`=i.`ID` SET s.`ItemID`=i.`ID`,s.`Itemdescription`=i.`Description` WHERE a.`Alias`= '$ItemLookupCode' AND s.`ID`=$id");

        /** Updating sheet entry StocktakeID */
        $query1 = $this->db->query("UPDATE `tempsheets`s SET s. `StocktakeID` =(SELECT s.`ID` FROM `stocktake`s WHERE s.`Status`=0)
        WHERE s.`StocktakeID`=0");
    }
    /** Updating tempsheets when an entry is updated */
    public function tempsheet_update($id, $ItemLookupCode)
    {
        /** When a user updates an item with ItemLookupCode*/
        $query = $this->db->query("UPDATE `tempsheets`s JOIN `stocktake_entry`e ON s.`ItemLookupCode`=e.`ItemLookupCode` SET s.`ItemID`=e.`ItemID`,s.`Itemdescription`=e.`Description` WHERE s.`ItemLookupCode`= '$ItemLookupCode' AND s.`ID`=$id");
        /** When a user updates an item with an Alias code */
        $query0 = $this->db->query("UPDATE `tempsheets`s JOIN `alias`a ON s.`ItemLookupCode`=a.`Alias` JOIN `item`i ON a.`ItemID`=i.`ID` SET s.`ItemID`=i.`ID`,s.`Itemdescription`=i.`Description` WHERE a.`Alias`= '$ItemLookupCode' AND s.`ID`=$id");

        /** Updating sheet entry StocktakeID */
        /** $query1 = $this->db->query("UPDATE `tempsheets`s SET s. `StocktakeID` =(SELECT s.`ID` FROM `stocktake`s WHERE s.`Status`=0) AND s.`StocktakeID`=0"); */
    }
    /** Bin sheets report */
    public function binsheets()
    {
        $this->db->SELECT("s.`bin` AS bin,s.`ItemLookupCode` AS itemcode,s.`Description`,s.`CountedDate` AS fedtime,i.`Cost`,i.`Price`,s.`Quantity`,s.`Username` AS username ");
        $this->db->JOIN('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->ORDER_BY('s.`bin`');
        $query = $this->db->get('`stocksheets` s');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Show all the items that are pending synching */
    public function syncstocksheets()
    {
        $this->db->SELECT("s.*,a.`Alias`,i.`ItemLookupCode` as ItemCode,i.`Cost`,i.`Price`");
        $this->db->JOIN('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->JOIN('`alias` a', 'a.`ItemID`=s.`ItemID`', 'LEFT');
        $this->db->WHERE('s.`Status`', 0);
        $this->db->GROUP_BY('s.`ID`');
        $this->db->ORDER_BY('s.`Quantity`', 'ASC');
        $this->db->ORDER_BY('s.`ID`', 'DESC');
        // $this->db->LIMIT('100');

        $query = $this->db->get('`stocksheets` s');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Stocktake progress value */
    public function syncstocksheetsval()
    {
        $this->db->SELECT("IFNULL(SUM(i.`Cost`*s.`Quantity`),0) as total");
        $this->db->JOIN('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->WHERE('s.`Status`', 0);

        $query = $this->db->get('`stocksheets` s');

        if ($query) {
            if ($query->row() <> '') {
                return $query->row()->total;
            } else {
                return 0;
            }
        }
    }
    /** Specific item  */
    public function specific_feed($LookupCode)
    {
        $this->db->SELECT("s.*,a.`Alias`,i.`ItemLookupCode` as ItemCode,i.`Cost`,i.`Price`");
        $this->db->JOIN('`item` i', 'i.`ID`=s.`ItemID`');
        $this->db->JOIN('`alias` a', 'a.`ItemID`=i.`ID`', 'LEFT');
        $this->db->WHERE('s.`ItemLookupCode`', $LookupCode);
        $this->db->WHERE('s.`Status`', 0);
        $this->db->ORDER_BY('s.`ID`', 'DESC');

        $query = $this->db->get('`stocksheets` s');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Temporary stock sheets report */
    public function tempsheets()
    {
        $this->db->SELECT("t.*");
        $this->db->JOIN('`stocktake` s', 's.`ID`=t.`StocktakeID`');
        $this->db->WHERE('t.`UserID`', $this->session->userdata('ID'));
        $this->db->WHERE('t.`Status`', 0);
        $this->db->WHERE('s.`Status`', 0);
        $this->db->ORDER_BY('t.`tTime`', 'DESC');
        $this->db->ORDER_BY('t.`ID`', 'DESC');

        $query = $this->db->get('`tempsheets` t');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Selecting reason code */
    public function reasoncode()
    {
        $this->db->SELECT('r.`Description` as reason');
        $this->db->WHERE('r.`Description`', 'Stock Take');
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
        $this->db->SELECT(" (s.`Shelf`)  AS shelf");
        $this->db->JOIN('`stocktake` t', 't.`ID`=s.`StocktakeID`');
        $this->db->WHERE('s.`UserID`', $this->session->userdata('ID'));
        $this->db->WHERE('s.Status', 0);
        $this->db->WHERE('t.`Status`', 0);
        $this->db->ORDER_BY('s.`ID`', 'DESC');
        $this->db->LIMIT('1');

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
        $this->db->SELECT("l.`Description`,s.`ID`,s.`Status`,s.`CountingDate`,s.`InitiatedByName`,s.`CommittedName`,s.`DateCommitted`");
        $this->db->JOIN('`inventorylocation` l', 'l.`ID`=s.`StoreID`');
        $this->db->WHERE('s.`Status`<>', 0);
        $this->db->ORDER_BY('s.`ID`', 'ASC');

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
    /** Department listings */
    public function details($id)
    {
        $this->db->SELECT("e.`ItemLookupCode` as lookup,a.`Alias`,e.`Description`,d.`Name` AS department,e.`Cost`,e.`OriginalQty`,e.`CountedQty`");
        $this->db->JOIN('`department` d', 'd.`ID`=e.`DepartmentID`', 'LEFT');
        $this->db->JOIN('`alias` a', 'a.`ItemID`=e.`ItemID`', 'LEFT');
        $this->db->WHERE('e.`StocktakeID`', $id);
        $this->db->WHERE('e.`CountedQty`>', 0);
        $this->db->GROUP_BY('e.`ItemID`');
        $this->db->ORDER_BY('e.`Description`', 'ASC');
        /** $this->db->LIMIT('100'); */

        $query = $this->db->get('`stocktake_entry` e');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else
            return false;
    }
    /** Getting stock take ID */
    public function get_record($id)
    {
        $this->db->WHERE('s.`ID`', $id);

        $query = $this->db->get('`stocktake` s');

        if ($query->num_rows() > 0)
            return $query->result();
    }
}
