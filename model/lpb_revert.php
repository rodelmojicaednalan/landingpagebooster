<?php
/**
 * LandingPageBooster lpb_revert
 *
 * update API Method
 *
 * @class 		lpb_revert
 * @version		1.0.0
 * @package		LandingPageBooster/Classes
 * @category	Class
 * @author 		Netseek
 */ 
require_once( ABSPATH . '/wp-load.php' );
if ( ! class_exists( 'lpb_revert' ) ) :
class lpb_revert extends ZipArchive
{
	private $last_percent = 0;
	private $old_version;
	private $loop = 0;
	/**
     * The plugin new version
     * @var string
     */
    public $api_response;
    /**
     * The plugin current version
     * @var string
     */
    public $current_version;
 
    /**
     * The plugin remote update path
     * @var string
     */
    public $update_path;
 
    /**
     * Plugin extract (plugin_directory/plugin_file.php)
     * @var string
     */
    public $file_extract;/**
     * Plugin message status update
     * @var string
     */
    public $msg_uprgradestat;
    /**
     * Initialize a new instance of the Landing Page Booster class
     * @param string $current_version
     * @param string $update_path
     * @param string $file_extract
     */
    function __construct( $response,$current_version ,$file_extract,$old_version)
    {
        // Set the class public variables
		$this->api_response = $response;
		$this->current_version = $current_version;
		$this->update_path =$response->download_link;
		$this->file_extract = $file_extract;
		$this->old_version = $old_version;
		
		if(isset($_POST['revert']) == true)
		{
			
			$this->revert_old_plugin($this->api_response);
		}
    }
	
	// revert the previous version update
	 public function revert_old_plugin($api_response)
    {
		   $remote_version = $api_response->version;
		   return $this->get_revert(); 
         
	}
	
	//Jquery show message status rollback
	 public function message_status_rollback($message)
    { 
		?>
		<script>
		setTimeout(function(){
			alert("<?php echo $message;?>");
			document.getElementById("progress-complete").innerHTML ="Please Wait";
			var span = document.getElementById('progress-complete');
			var dot=0;
			var int = setInterval(function() {
				span.innerHTML += '.';
				dot++;
				if (dot == 4){
					span.innerHTML = "Please Wait";
					dot=0;
					}
				//clearInterval( int ); // at some point, clear the setInterval
			}, 1000);
			
			setTimeout(function(){	window.location=<?php home_url(); ?>'/wp-admin/admin.php?page=krurls';}, 4000);
				}, 1000);
		</script>
		<?php 
	}
	public function get_revert()
	{ 
		$updatecmd = true;
		add_action( 'admin_notices', array( $this , 'admin_modal_revert' )); 
        return $updatecmd;
	} 
	
	//Display html modal rollback
	public function admin_modal_revert()
	{
	?>
	    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<div class="modal bootstrap-dialog type-primary fade size-normal in" role="dialog" aria-hidden="true" id="348b8268-29c2-4cd9-9670-644b8a2380da" aria-labelledby="348b8268-29c2-4cd9-9670-644b8a2380da_title" tabindex="-1" style="display:block">
		<div class="modal-dialog">
		<div class="modal-content" style="top: 259px;">
		<div class="modal-header" style="background: #23282d;color:white; border-bottom: none!important; "> 

		<div class="bootstrap-dialog-header">
		<div class="bootstrap-dialog-message"><b><span id="progress-title">Progress</span></b></div><br>
		<div class="progress progress-striped active">
		<div id="prograss-bar" class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:0;background-color: rgb(220, 50, 50);">
		<span  style="position:static!important;" id="progress-complete" class="sr-only"></span>  

		</div>

		</div>
		<div id="change-progress"></div>   

		</div>
		</div>
		</div>
		</div>
		</div>
		<?php 
		 //progress bar process
		 $the_folder = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/landing-page-booster";
		   $this_file_name = $_SERVER['DOCUMENT_ROOT']."/wp-content/uploads/landing-page-booster-backup.zip";
		   $msg_changelog;
		   if(isset($_POST['revert_btn']))  
		   {
		   if ($this->open( $this_file_name) === TRUE)
		   {
			 for($i = 0; $i <= $this->numFiles;$i++ )
			 {
				$this->extractTo($the_folder, array($this->getNameIndex($i)));
				
				$temp = $this->getNameIndex($i);
				if(!empty($temp))
				{
					$msg_changelog = "Reverted File - ".$this->getNameIndex($i);
				}
				else
				{
					 $msg_changelog = "";
				}
				$this->progressbar_process($i,$this->numFiles,100,array("title" => "Landing Page Booster Rollback" ,"changeprogress" => $msg_changelog),$backuppercent);
			   /* This is for the buffer achieve the minimum size in order to flush data */
				 echo str_repeat(' ',(500)*$this->numFiles);
				/* Send output to browser immediately */
				flush();
				/* Sleep one milisecond so we can see the delay */
				usleep(100000);
					
			 }
				unlink($this_file_name);
				$this->close();
				setcookie('version_cookie', null, -1);
				$this->message_status_rollback("Rollback successful");
				
			}
			else{
				$this->message_status_rollback("Rollback failed");
				
			}
		   }
	}
	
 /* Progress bar UI porcess Jquery.
 * 
 * 
 * @param loop count , file count , percent range , data array 
 */ 
function progressbar_process($count=0,$file_num=1,$range_percent = 100,$data="",$last_percentage = 0)
{		
		$percent = (floor($count / $file_num * $range_percent) + abs($this->loop) * $range_percent) ; 
		$percent = ($percent + $last_percentage)."%";
		echo '<script> 
				   document.getElementById("prograss-bar").style.width = "'. $percent.'";
				   document.getElementById("progress-complete").innerHTML ="' . str_replace("-", "", ($percent-100)).'% Remaining";
				   document.getElementById("change-progress").innerHTML ="' .$data['changeprogress'].'";
				   document.getElementById("progress-title").innerHTML ="' .$data['title'].'";
				 
			 </script>';
	return $percent;
}

//html Dialog confirm 
function confirm_revert( $classes)
	{
	?>
	<link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/wp-content/plugins/landing-page-booster/assets/lpb-update.css" type="text/css" media="screen" />
	<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog" style="z-index: 99999;">
	<!-- Modal content-->
	<div class="modal-content" >
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Rollback to previous version</h4></div>
	<div class="modal-body">
	<div class="bootstrap-dialog-body">
	<div class="bootstrap-dialog-body">
	<div class="bootstrap-dialog-message">Are you sure you want to rollback from the previous version <?php echo $this->old_version;?>?</div>
	</div>   
	</div>
	</div>
	<div class="modal-footer" style="display: block;">
	<div class="bootstrap-dialog-footer">
	<div class="bootstrap-dialog-footer-buttons">
	<form style="display: inline-block;" method='post' action="<?php admin_url( 'admin.php?page=krurls' ); ?> ">
	<button  type='submit' name='revert' value='true' class="btn btn-default update" >Yes</button>
	<input type='hidden' name='revert_btn' value='true'>
	</form>
	<button class="btn btn-default" data-dismiss="modal">No</button>
	</div> 
	</div>
	</div>
	</div>
	</div>
	</div> 
	<?php 
		
	}
}
endif;
?>