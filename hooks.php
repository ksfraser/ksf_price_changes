<?php

define( 'MENU_IMPORT', 'menu_import' );

class hooks_ksf_price_changes extends hooks {
    var $module_name = 'ksf_price_changes'; 

    /*
    * Install additonal menu options provided by module
    */

    function install_options($app) {
	global $path_to_root;


	switch($app->id) {
	    case 'stock':
		$app->add_lapp_function(3, _("Display Changed Prices Since"),
			$path_to_root."/modules/".$this->module_name."/changed_prices.php", 'SA_BANKACCOUNT', MENU_IMPORT);

		break;
	}
    }


    function activate_extension($company, $check_only=true) {
	$updates = array( 'update.sql' => array($this->module_name) );
	return $this->update_databases($company, $updates, $check_only);
	return true;
    }

    //this is required to cancel bank transactions when a voiding operation occurs
    function db_prevoid($trans_type, $trans_no) {
/**
	    //SET status=0
	$sql = "
	    UPDATE ".TB_PREF."bi_transactions
	    SET status=0, fa_trans_no=0, fa_trans_type=0
	    WHERE
		fa_trans_no=".db_escape($trans_no)." AND
		fa_trans_type=".db_escape($trans_type)." AND
		status = 1";
	display_notification($sql);
	db_query($sql, 'Could not void transaction');
**/
    }


}
?>
