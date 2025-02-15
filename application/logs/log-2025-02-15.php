<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

INFO - 2025-02-15 12:59:36 --> Config Class Initialized
INFO - 2025-02-15 12:59:36 --> Hooks Class Initialized
DEBUG - 2025-02-15 12:59:36 --> UTF-8 Support Enabled
INFO - 2025-02-15 12:59:36 --> Utf8 Class Initialized
INFO - 2025-02-15 12:59:36 --> URI Class Initialized
INFO - 2025-02-15 12:59:36 --> Router Class Initialized
INFO - 2025-02-15 12:59:36 --> Output Class Initialized
INFO - 2025-02-15 12:59:36 --> Security Class Initialized
DEBUG - 2025-02-15 12:59:36 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 12:59:36 --> Input Class Initialized
INFO - 2025-02-15 12:59:36 --> Language Class Initialized
INFO - 2025-02-15 12:59:36 --> Loader Class Initialized
INFO - 2025-02-15 12:59:36 --> Helper loaded: url_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: file_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: html_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: string_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: date_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: email_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: security_helper
INFO - 2025-02-15 12:59:36 --> Database Driver Class Initialized
DEBUG - 2025-02-15 12:59:36 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 12:59:36 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 12:59:36 --> Upload Class Initialized
INFO - 2025-02-15 12:59:36 --> Helper loaded: form_helper
INFO - 2025-02-15 12:59:36 --> Form Validation Class Initialized
INFO - 2025-02-15 12:59:36 --> User Agent Class Initialized
INFO - 2025-02-15 12:59:36 --> Model "Stocktake" initialized
INFO - 2025-02-15 12:59:36 --> Controller Class Initialized
INFO - 2025-02-15 12:59:36 --> File loaded: /var/www/html/stocktake/application/views/common/header.php
INFO - 2025-02-15 12:59:36 --> File loaded: /var/www/html/stocktake/application/views/common/menu.php
INFO - 2025-02-15 12:59:36 --> File loaded: /var/www/html/stocktake/application/views/holdings.php
INFO - 2025-02-15 12:59:36 --> File loaded: /var/www/html/stocktake/application/views/common/footer.php
INFO - 2025-02-15 12:59:36 --> Final output sent to browser
DEBUG - 2025-02-15 12:59:36 --> Total execution time: 0.0127
INFO - 2025-02-15 12:59:36 --> Config Class Initialized
INFO - 2025-02-15 12:59:36 --> Hooks Class Initialized
DEBUG - 2025-02-15 12:59:36 --> UTF-8 Support Enabled
INFO - 2025-02-15 12:59:36 --> Utf8 Class Initialized
INFO - 2025-02-15 12:59:36 --> URI Class Initialized
INFO - 2025-02-15 12:59:36 --> Router Class Initialized
INFO - 2025-02-15 12:59:36 --> Output Class Initialized
INFO - 2025-02-15 12:59:36 --> Security Class Initialized
DEBUG - 2025-02-15 12:59:36 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 12:59:36 --> Input Class Initialized
INFO - 2025-02-15 12:59:36 --> Language Class Initialized
INFO - 2025-02-15 12:59:36 --> Loader Class Initialized
INFO - 2025-02-15 12:59:36 --> Helper loaded: url_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: file_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: html_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: string_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: date_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: email_helper
INFO - 2025-02-15 12:59:36 --> Helper loaded: security_helper
INFO - 2025-02-15 12:59:36 --> Database Driver Class Initialized
DEBUG - 2025-02-15 12:59:36 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 12:59:36 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 12:59:36 --> Upload Class Initialized
INFO - 2025-02-15 12:59:36 --> Helper loaded: form_helper
INFO - 2025-02-15 12:59:36 --> Form Validation Class Initialized
INFO - 2025-02-15 12:59:36 --> User Agent Class Initialized
INFO - 2025-02-15 12:59:36 --> Model "Stocktake" initialized
INFO - 2025-02-15 12:59:36 --> Controller Class Initialized
DEBUG - 2025-02-15 12:59:36 --> Last Query: SELECT COUNT(*) AS `numrows`
FROM (
SELECT `d`.`Name` AS `department`, `c`.`Name` AS `category`, `e`.`Name` AS `subcategory`, `s`.`ItemID` AS `ItemID`, `s`.`ItemLookupCode` AS `Code`, `s`.`Description` AS `description`, `s`.`Cost` AS `Cost`, (s.Cost*s.OriginalQty) as availableTotal, (s.Cost*s.CountedQty) as countedTotal, `s`.`OriginalQty` AS `OriginalQty`, `s`.`CountedQty` AS `CountedQty`, DATE(s.`CountedDate`) AS CountedDate, `a`.`Alias`
FROM `stocktake_entry` `s`
LEFT JOIN `department` `d` ON d.`ID`=s.`DepartmentID`
LEFT JOIN `category` `c` ON c.`ID`=s.`CategoryID`
LEFT JOIN `subcategory` `e` ON e.`ID`=s.`SubCategoryID`
LEFT JOIN `alias` `a` ON a.`ItemID`=s.`ItemID`
WHERE `s`.`CountedQty` > 0
AND `s`.`Status` = 0
GROUP BY `s`.`ID`
) CI_count_all_results
INFO - 2025-02-15 12:59:36 --> Final output sent to browser
DEBUG - 2025-02-15 12:59:36 --> Total execution time: 0.3007
INFO - 2025-02-15 13:02:12 --> Config Class Initialized
INFO - 2025-02-15 13:02:12 --> Hooks Class Initialized
DEBUG - 2025-02-15 13:02:12 --> UTF-8 Support Enabled
INFO - 2025-02-15 13:02:12 --> Utf8 Class Initialized
INFO - 2025-02-15 13:02:12 --> URI Class Initialized
INFO - 2025-02-15 13:02:12 --> Router Class Initialized
INFO - 2025-02-15 13:02:12 --> Output Class Initialized
INFO - 2025-02-15 13:02:12 --> Security Class Initialized
DEBUG - 2025-02-15 13:02:12 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 13:02:12 --> Input Class Initialized
INFO - 2025-02-15 13:02:12 --> Language Class Initialized
INFO - 2025-02-15 13:02:12 --> Loader Class Initialized
INFO - 2025-02-15 13:02:12 --> Helper loaded: url_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: file_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: html_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: string_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: date_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: email_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: security_helper
INFO - 2025-02-15 13:02:12 --> Database Driver Class Initialized
DEBUG - 2025-02-15 13:02:12 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 13:02:12 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 13:02:12 --> Upload Class Initialized
INFO - 2025-02-15 13:02:12 --> Helper loaded: form_helper
INFO - 2025-02-15 13:02:12 --> Form Validation Class Initialized
INFO - 2025-02-15 13:02:12 --> User Agent Class Initialized
INFO - 2025-02-15 13:02:12 --> Model "Stocktake" initialized
INFO - 2025-02-15 13:02:12 --> Controller Class Initialized
INFO - 2025-02-15 13:02:12 --> File loaded: /var/www/html/stocktake/application/views/common/header.php
INFO - 2025-02-15 13:02:12 --> File loaded: /var/www/html/stocktake/application/views/common/menu.php
INFO - 2025-02-15 13:02:12 --> File loaded: /var/www/html/stocktake/application/views/holdings.php
INFO - 2025-02-15 13:02:12 --> File loaded: /var/www/html/stocktake/application/views/common/footer.php
INFO - 2025-02-15 13:02:12 --> Final output sent to browser
DEBUG - 2025-02-15 13:02:12 --> Total execution time: 0.0168
INFO - 2025-02-15 13:02:12 --> Config Class Initialized
INFO - 2025-02-15 13:02:12 --> Hooks Class Initialized
DEBUG - 2025-02-15 13:02:12 --> UTF-8 Support Enabled
INFO - 2025-02-15 13:02:12 --> Utf8 Class Initialized
INFO - 2025-02-15 13:02:12 --> URI Class Initialized
INFO - 2025-02-15 13:02:12 --> Router Class Initialized
INFO - 2025-02-15 13:02:12 --> Output Class Initialized
INFO - 2025-02-15 13:02:12 --> Security Class Initialized
DEBUG - 2025-02-15 13:02:12 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 13:02:12 --> Input Class Initialized
INFO - 2025-02-15 13:02:12 --> Language Class Initialized
INFO - 2025-02-15 13:02:12 --> Loader Class Initialized
INFO - 2025-02-15 13:02:12 --> Helper loaded: url_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: file_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: html_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: string_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: date_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: email_helper
INFO - 2025-02-15 13:02:12 --> Helper loaded: security_helper
INFO - 2025-02-15 13:02:12 --> Database Driver Class Initialized
DEBUG - 2025-02-15 13:02:12 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 13:02:12 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 13:02:12 --> Upload Class Initialized
INFO - 2025-02-15 13:02:12 --> Helper loaded: form_helper
INFO - 2025-02-15 13:02:12 --> Form Validation Class Initialized
INFO - 2025-02-15 13:02:12 --> User Agent Class Initialized
INFO - 2025-02-15 13:02:12 --> Model "Stocktake" initialized
INFO - 2025-02-15 13:02:12 --> Controller Class Initialized
DEBUG - 2025-02-15 13:02:12 --> Last Query: SELECT COUNT(*) AS `numrows`
FROM (
SELECT `d`.`Name` AS `department`, `c`.`Name` AS `category`, `e`.`Name` AS `subcategory`, `s`.`ItemID` AS `ItemID`, `s`.`ItemLookupCode` AS `Code`, `s`.`Description` AS `description`, `s`.`Cost` AS `Cost`, (s.Cost*s.OriginalQty) as availableTotal, (s.Cost*s.CountedQty) as countedTotal, `s`.`OriginalQty` AS `OriginalQty`, `s`.`CountedQty` AS `CountedQty`, DATE(s.`CountedDate`) AS CountedDate, `a`.`Alias`
FROM `stocktake_entry` `s`
LEFT JOIN `department` `d` ON d.`ID`=s.`DepartmentID`
LEFT JOIN `category` `c` ON c.`ID`=s.`CategoryID`
LEFT JOIN `subcategory` `e` ON e.`ID`=s.`SubCategoryID`
LEFT JOIN `alias` `a` ON a.`ItemID`=s.`ItemID`
WHERE `s`.`CountedQty` > 0
AND `s`.`Status` = 0
GROUP BY `s`.`ID`
) CI_count_all_results
INFO - 2025-02-15 13:02:12 --> Final output sent to browser
DEBUG - 2025-02-15 13:02:12 --> Total execution time: 0.3073
INFO - 2025-02-15 13:02:59 --> Config Class Initialized
INFO - 2025-02-15 13:02:59 --> Hooks Class Initialized
DEBUG - 2025-02-15 13:02:59 --> UTF-8 Support Enabled
INFO - 2025-02-15 13:02:59 --> Utf8 Class Initialized
INFO - 2025-02-15 13:02:59 --> URI Class Initialized
INFO - 2025-02-15 13:02:59 --> Router Class Initialized
INFO - 2025-02-15 13:02:59 --> Output Class Initialized
INFO - 2025-02-15 13:02:59 --> Security Class Initialized
DEBUG - 2025-02-15 13:02:59 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 13:02:59 --> Input Class Initialized
INFO - 2025-02-15 13:02:59 --> Language Class Initialized
INFO - 2025-02-15 13:02:59 --> Loader Class Initialized
INFO - 2025-02-15 13:02:59 --> Helper loaded: url_helper
INFO - 2025-02-15 13:02:59 --> Helper loaded: file_helper
INFO - 2025-02-15 13:02:59 --> Helper loaded: html_helper
INFO - 2025-02-15 13:02:59 --> Helper loaded: string_helper
INFO - 2025-02-15 13:02:59 --> Helper loaded: date_helper
INFO - 2025-02-15 13:02:59 --> Helper loaded: email_helper
INFO - 2025-02-15 13:02:59 --> Helper loaded: security_helper
INFO - 2025-02-15 13:02:59 --> Database Driver Class Initialized
DEBUG - 2025-02-15 13:02:59 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 13:02:59 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 13:02:59 --> Upload Class Initialized
INFO - 2025-02-15 13:02:59 --> Helper loaded: form_helper
INFO - 2025-02-15 13:02:59 --> Form Validation Class Initialized
INFO - 2025-02-15 13:02:59 --> User Agent Class Initialized
INFO - 2025-02-15 13:02:59 --> Model "Stocktake" initialized
INFO - 2025-02-15 13:02:59 --> Controller Class Initialized
INFO - 2025-02-15 13:02:59 --> File loaded: /var/www/html/stocktake/application/views/common/header.php
INFO - 2025-02-15 13:02:59 --> File loaded: /var/www/html/stocktake/application/views/common/menu.php
INFO - 2025-02-15 13:02:59 --> File loaded: /var/www/html/stocktake/application/views/holdings.php
INFO - 2025-02-15 13:02:59 --> File loaded: /var/www/html/stocktake/application/views/common/footer.php
INFO - 2025-02-15 13:02:59 --> Final output sent to browser
DEBUG - 2025-02-15 13:02:59 --> Total execution time: 0.0127
INFO - 2025-02-15 13:03:00 --> Config Class Initialized
INFO - 2025-02-15 13:03:00 --> Hooks Class Initialized
DEBUG - 2025-02-15 13:03:00 --> UTF-8 Support Enabled
INFO - 2025-02-15 13:03:00 --> Utf8 Class Initialized
INFO - 2025-02-15 13:03:00 --> URI Class Initialized
INFO - 2025-02-15 13:03:00 --> Router Class Initialized
INFO - 2025-02-15 13:03:00 --> Output Class Initialized
INFO - 2025-02-15 13:03:00 --> Security Class Initialized
DEBUG - 2025-02-15 13:03:00 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 13:03:00 --> Input Class Initialized
INFO - 2025-02-15 13:03:00 --> Language Class Initialized
INFO - 2025-02-15 13:03:00 --> Loader Class Initialized
INFO - 2025-02-15 13:03:00 --> Helper loaded: url_helper
INFO - 2025-02-15 13:03:00 --> Helper loaded: file_helper
INFO - 2025-02-15 13:03:00 --> Helper loaded: html_helper
INFO - 2025-02-15 13:03:00 --> Helper loaded: string_helper
INFO - 2025-02-15 13:03:00 --> Helper loaded: date_helper
INFO - 2025-02-15 13:03:00 --> Helper loaded: email_helper
INFO - 2025-02-15 13:03:00 --> Helper loaded: security_helper
INFO - 2025-02-15 13:03:00 --> Database Driver Class Initialized
DEBUG - 2025-02-15 13:03:00 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 13:03:00 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 13:03:00 --> Upload Class Initialized
INFO - 2025-02-15 13:03:00 --> Helper loaded: form_helper
INFO - 2025-02-15 13:03:00 --> Form Validation Class Initialized
INFO - 2025-02-15 13:03:00 --> User Agent Class Initialized
INFO - 2025-02-15 13:03:00 --> Model "Stocktake" initialized
INFO - 2025-02-15 13:03:00 --> Controller Class Initialized
DEBUG - 2025-02-15 13:03:00 --> Last Query: SELECT COUNT(*) AS `numrows`
FROM (
SELECT `e`.`ItemID`, `a`.`Alias`, `e`.`ItemLookupCode` as `itemcode`, `e`.`Description`, `e`.`BinLocation` as `bin`, `e`.`Price`, `e`.`Cost`, `e`.`OriginalQty`, DATE(s.`CountingDate`) as CountingDate, (e.Cost*e.OriginalQty) as availableTotal, (e.Cost*e.CountedQty) as countedTotal, `e`.`CountedQty`
FROM `stocktake_entry` `e`
JOIN `stocktake` `s` ON s.`ID` = e.`StocktakeID`
LEFT JOIN `alias` `a` ON a.`ItemID`=e.`ItemID`
WHERE `s`.`Status` = 0
AND `e`.`OriginalQty` >= 0
GROUP BY `e`.`ItemID`
) CI_count_all_results
INFO - 2025-02-15 13:03:00 --> Final output sent to browser
DEBUG - 2025-02-15 13:03:00 --> Total execution time: 0.5680
INFO - 2025-02-15 13:03:15 --> Config Class Initialized
INFO - 2025-02-15 13:03:15 --> Hooks Class Initialized
DEBUG - 2025-02-15 13:03:15 --> UTF-8 Support Enabled
INFO - 2025-02-15 13:03:15 --> Utf8 Class Initialized
INFO - 2025-02-15 13:03:15 --> URI Class Initialized
INFO - 2025-02-15 13:03:15 --> Router Class Initialized
INFO - 2025-02-15 13:03:15 --> Output Class Initialized
INFO - 2025-02-15 13:03:15 --> Security Class Initialized
DEBUG - 2025-02-15 13:03:15 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-02-15 13:03:15 --> Input Class Initialized
INFO - 2025-02-15 13:03:15 --> Language Class Initialized
INFO - 2025-02-15 13:03:15 --> Loader Class Initialized
INFO - 2025-02-15 13:03:15 --> Helper loaded: url_helper
INFO - 2025-02-15 13:03:15 --> Helper loaded: file_helper
INFO - 2025-02-15 13:03:15 --> Helper loaded: html_helper
INFO - 2025-02-15 13:03:15 --> Helper loaded: string_helper
INFO - 2025-02-15 13:03:15 --> Helper loaded: date_helper
INFO - 2025-02-15 13:03:15 --> Helper loaded: email_helper
INFO - 2025-02-15 13:03:15 --> Helper loaded: security_helper
INFO - 2025-02-15 13:03:15 --> Database Driver Class Initialized
DEBUG - 2025-02-15 13:03:15 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2025-02-15 13:03:15 --> Session: Class initialized using 'files' driver.
INFO - 2025-02-15 13:03:15 --> Upload Class Initialized
INFO - 2025-02-15 13:03:15 --> Helper loaded: form_helper
INFO - 2025-02-15 13:03:15 --> Form Validation Class Initialized
INFO - 2025-02-15 13:03:15 --> User Agent Class Initialized
INFO - 2025-02-15 13:03:15 --> Model "Stocktake" initialized
INFO - 2025-02-15 13:03:15 --> Controller Class Initialized
DEBUG - 2025-02-15 13:03:15 --> Last Query: SELECT COUNT(*) AS `numrows`
FROM (
SELECT `e`.`ItemID`, `a`.`Alias`, `e`.`ItemLookupCode` as `itemcode`, `e`.`Description`, `e`.`BinLocation` as `bin`, `e`.`Price`, `e`.`Cost`, `e`.`OriginalQty`, DATE(s.`CountingDate`) as CountingDate, (e.Cost*e.OriginalQty) as availableTotal, (e.Cost*e.CountedQty) as countedTotal, `e`.`CountedQty`
FROM `stocktake_entry` `e`
JOIN `stocktake` `s` ON s.`ID` = e.`StocktakeID`
LEFT JOIN `alias` `a` ON a.`ItemID`=e.`ItemID`
WHERE `s`.`Status` = 0
AND `e`.`OriginalQty` >= 0
GROUP BY `e`.`ItemID`
) CI_count_all_results
INFO - 2025-02-15 13:03:15 --> Final output sent to browser
DEBUG - 2025-02-15 13:03:15 --> Total execution time: 0.5560
