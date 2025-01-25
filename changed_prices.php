<?php

$path_to_root = "../..";
$page_security = 'SA_SALESTRANSVIEW';
include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/session.inc");

include_once($path_to_root . "/includes/ui/ui_input.inc");
include_once($path_to_root . "/includes/ui/ui_lists.inc");
include_once($path_to_root . "/includes/ui/ui_globals.inc");
include_once($path_to_root . "/includes/ui/ui_controls.inc");
include_once($path_to_root . "/includes/ui/items_cart.inc");
include_once($path_to_root . "/includes/data_checks.inc");

include_once($path_to_root . "/modules/ksf_modules_common/class.fa_prices.php");


//TODO:


$js = "";
if ($use_popup_windows)
	$js .= get_js_open_window(900, 500);
if ($use_date_picker)
	$js .= get_js_date_picker();

page(_($help_context = "Changed Prices"), @$_GET['popup'], false, "", $js);


include_once($path_to_root . "/modules/ksf_modules_common/defines.inc.php");
/**
$trans_types_readable = array( 
	ST_JOURNAL => "Journal Entry",
	ST_BANKPAYMENT => "Bank Payment",
	ST_BANKDEPOSIT => "Bank Deposit",
	ST_BANKTRANSFER => "Bank Transfer",
	ST_SALESINVOICE => "Sales Invoice",
	ST_CUSTCREDIT => "Customer Credit",
	ST_CUSTPAYMENT => "Customer Payment",
	ST_CUSTDELIVERY => "Customer Delivery",
	ST_LOCTRANSFER => "Location Transfer",
	ST_INVADJUST => "Inventory Adjustment",
	ST_PURCHORDER => "Purchase Order",
	ST_SUPPINVOICE => "Supplier Invoice",
	ST_SUPPCREDIT => "Supplier Credit",
	ST_SUPPAYMENT => "Supplier Payment",
	ST_SUPPRECEIVE => "Supplier Received",
	ST_WORKORDER => "Work Order",
	ST_MANUISSUE => "Manufacturing Issue",
	ST_MANURECEIVE => "Manufacturing Receive",
	ST_SALESORDER => "Sales Order",
	ST_SALESQUOTE => "Sales Quote",
	ST_COSTUPDATE => "Cost Update",
	ST_DIMENSION => "Dimension",
);
**/

/************************************************************************************************************************/
/**********************************************  GUI  *******************************************************************/
/************************************************************************************************************************/

// search button pressed
if (get_post('RefreshInquiry')) {
	$Ajax->activate('doc_tbl');
}

//SC: check whether a customer has been changed, so that we can update branch as well
// as there a user can click on one submit button only, there is no need for multiple check
unset($k, $v);
if (isset($_POST['partnerId'])) {
			//display_notification( __FILE__ . "::" . __LINE__ );
	list($k, $v) = each($_POST['partnerId']);
	if (isset($k) && isset($v)) {
		$Ajax->activate('doc_tbl');
	}
}

error_reporting(E_ALL);

start_form();

div_start('doc_tbl');

if (1) {
	//------------------------------------------------------------------------------------------------
	// this is filter table
	start_table(TABLESTYLE_NOBORDER);
	start_row();
	if (!isset($_POST['statusFilter']))
		$_POST['statusFilter'] = 0;
	if (!isset($_POST['TransAfterDate']))
		$_POST['TransAfterDate'] = begin_month(Today());

	date_cells(_("From:"), 'TransAfterDate', '', null, -30);
	submit_cells('RefreshInquiry', _("Search"),'',_('Refresh Inquiry'), 'default');
	end_row();
	end_table();

	//if (!@$_GET['popup'])
	//	end_form();


/*************************************************************************************************************/
/***********************************  Transactions  **********************************************************/
/*************************************************************************************************************/
	//------------------------------------------------------------------------------------------------
	// this is data display table

	$prices = new fa_prices();
	//Returns from db_fetch
	$prices->get_price_updated_since( $updated_since );

/*************************************************************************************************************/
	start_table(TABLESTYLE, "width='100%'");
	table_header(array("Transaction Details", "Operation/Status"));

	//load data
	$rows = array;
	while( $myrow = db_fetch( $prices->query_result ) ) 
	{
		//display_notification( __FILE__ . "::" . __LINE__ );
/*
stock_id      | varchar(64) | YES  | MUL | NULL    |                |
| sales_type_id | int(11)     | NO   |     | 0       |                |
| curr_abrev    | char(3)     | NO   |     |         |                |
| price         | double      | NO   |     | 0       |
| last_updated  | timestamp   | NO   |     | current_timestamp | ON UPDATE CURRENT_TIMESTAMP()
*/
	
		start_row();
		//echo '<td width="50%">';
		echo '<td>';
		echo $myrow['stock_id'];
		echo '</td>';
		echo '<td>';
		echo $myrow['curr_abrev'];
		echo '</td>';
		echo '<td>';
		echo $myrow['sales_type_id'];
		echo '</td>';
		echo '<td>';
		echo $myrow['price'];
		echo '</td>';
		echo '<td>';
		echo $myrow['last_updated'];
		echo '</td>';
		
			//label_row("Trans Date (Event Date):", $valueTimestamp . " :: (" . $trz['entryTimestamp'] . ")" , "width='25%' class='label'");
				//label_row("Add Vendor", submit("AddVendor[$tid]",_("AddVendor"),false, '', 'default'));
				//hidden( "vendor_short_$tid", $bankAccount );
		end_row();
	}
	end_table();
	div_end();
	end_form();

end_page(@$_GET['popup'], false, false);
?>
