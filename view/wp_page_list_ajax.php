<?php
/**
 * User Interface Record Page Listing AJAX
 *
 * @author 		Netseek
 * @category 	Admin
 * @package 	LandingPageBooster/Admin/View
 * @version     2.4.4
 */

ini_set('display_errors', 0);
error_reporting(0);


$ROOT_PATH  = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

require_once $ROOT_PATH . '/wp-config.php';
//require_once $ROOT_PATH . '/wp-load.php';
require_once $ROOT_PATH . '/wp-includes/wp-db.php';
require_once $ROOT_PATH . '/wp-content/plugins/landing-page-booster/model/sql.php';

$sql = new KwReplacerQuery();

if(isset($_GET['lastID']) && is_numeric($_GET['lastID']))
        $lastID =intval($_GET['lastID']);

$pages = $sql->GetKRPostNameLastID($lastID);

if(!empty($pages)){
foreach ($pages as $key => $val){
    ?>
    <tr id="<?php echo $key;?>">
        <td>/<?php echo  str_replace("-"," ", $val); ?>/
        </td>
         <td>
             <?php
             	if( $sql->is_page_default( $key ) ) {
             		echo '<span class="dashicons dashicons-yes"></span>';
             	}
             ?>
             </td>
        <td><center><a href="<?=get_bloginfo('wpurl').'/wp-admin/post.php?post='.$key.'&action=edit';?>">Set Page Defaults</a> </center></td>
    </tr>
    <?php
}
}
exit;

?>
