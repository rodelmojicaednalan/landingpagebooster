<?php
/**
 * User Interface Record Tags Listing AJAX
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
require_once $ROOT_PATH . '/wp-load.php';
require_once $ROOT_PATH . '/wp-includes/wp-db.php';
require_once $ROOT_PATH . '/wp-content/plugins/landing-page-booster/model/sql.php';

$sql = new KwReplacerQuery();

if(isset($_GET['lastID']) && is_numeric($_GET['lastID']))
        $lastID = intval($_GET['lastID']);

$page = $sql->GetKRPostNameByID($_GET['pageid']);
$tags = $sql->GetKRtagsLinksByPageIdLastId($_GET['pageid'],$lastID);
$base = network_site_url( '/' );

if(!empty ($tags)){
    $x = 0;
    foreach ($tags as $key => $val){
    ?>
    <tr id="<?php echo $val['id'];?>">
        <td ><?php echo $val['id']; ?></td>
        <td>/<?php echo str_replace("-"," ", $val['PageName']); ?>/</td>
        <td><a href="<?php echo $base . $plink  . $val['PageName'] . '/'. $val['Tags'];?>" target="_blank"><?php echo $plink . $val['PageName'] . '/'. $val['Tags']; ?></a></td>
        <td><?php
        if(!empty ($val['Data'])){
            echo $val['Data'];
        }else{
            echo 'No Tags Available';
        }
        ?></td>
        <td> 
            <a href="<?php echo $base . $plink  . $val['PageName'] . '/'. $val['Tags'];?>" target="_blank">View </a> |
            <a href="admin.php?page=AddKrwUrl&section=editpagestags&pageid=<?=$val['id']?>">Edit </a> |
            <a href="#" onclick="return deletes('<?=$val['id']?>','<?=$id?>');">Delete</a>
        </td>
    </tr>
    <?php
    }
}
        
exit;

?>
