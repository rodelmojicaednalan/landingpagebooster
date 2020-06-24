<?php 
/**
 * Instructions Display 
 *
 * Display the Instructions page in back end area of plugin
 *
 * @author 		Netseek
 * @category 	Admin
 * @package 	LandingPageBooster/Admin/View
 * @version     2.4.4
 */




?>



<style>
.LPB-Instructions {
    background: none repeat scroll 0 0 white;
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
    margin: 10px 0 20px;
    padding: 1em;
}

.LPB-table {
    width: 70%;
}

.LPB-table .LPB-th {
    width: 25%;
}

.LPB-table th, .LPB-table td {
    border-bottom: 1px solid #eee;
}

.LPB-table td span {
    display: block;
    padding: 5px 10px;
}

.LPB-color {
    color: #444 !important;
    font-family: "Open Sans",sans-serif !important;
    font-size: 14px !important;
    line-height: 1.4em !important;
}
</style>

<div class="wrap LPB-color" >
	 <h2><?php _e( 'Landing Page Booster Configuration Instructions' ) ?></h2>
	<div id="poststuff" class="LPB-Instructions" >
		<p class="LPB-color">Once Landing Page Booster has been installed and activated on your site, it is ready to use to pass parameters to any of your nominated landing pages.</p>
		<p class="LPB-color"> All you need to do to get started is to follow the steps below:</p>
		<p class="LPB-color"><strong>Step 1:</strong> First you need to pick a page that will become a Landing Page Booster landing page and set its defaults. <br> Default values can be setup for any page on your site from <a href="<?php echo  admin_url( 'admin.php?page=krurls' );?>">this link.</a></p>
		<p class="LPB-color"><strong>Step 2:</strong> Defaults for a page are entered into the following fields:</p>
		<p class="LPB-color"><img src="<?php echo plugins_url( '/assets/images/instructions.png',dirname( __FILE__) ); ?>"   width="800"></p>
		<p class="LPB-color"><strong>Step 3:</strong> Each of the fields above correspond to default elements of your landing page. <br>The importance of these is to ensure the page is aware of how to behave if keyword replacement parameters are not passed to it.<br> So that if a visitor visits your page by navigate to the URL directly, the page will still behave as a well intended landing page by default.</p>
		<p class="LPB-color"><strong>Step 4:</strong> These values can be setup as shown below:</p>
		
		<table class="LPB-table" style="width:100%;">
			<thead>
				<tr>
					<th class="LPB-th" style="width:20%;text-align:left;padding-left: 10px;"><?php _e( 'Default Field' )?></th>
					<th class="LPB-th" style="width:40%;text-align:left;padding-left: 10px;"><?php _e( 'Description' )?></th>
					<th class="LPB-th" style="width:40%;text-align:left;padding-left: 10px;"><?php _e( 'Example of a Value' )?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span ><?php esc_html_e('LPB Page Title');?></span></td>
					<td><span ><?php _e('This value is the page title contained within the '); esc_html_e('<TITLE>'); _e(' tag of your page. You can add general text here as well as ##Tag## values to fully make each page session keyword driven.');?></span></td>
					<td><span ><?php _e('Looking for a ##tag1## in ##tag2##? We provide exceptional ##tag1## services with a full guarantee');?></span></td>
				</tr> 
				<tr>
					<td><span ><?php _e('LPB META Description');?></span></td>
					<td><span ><?php _e('This value is the page META description and can be used to describe the page. You can add general text here as well as ##Tag## values to fully make each page session keyword driven.');?></span></td>
					<td><span ><?php _e('Established in 1999, we’ve been servicing the ##tag1## industry for over 15 years, servicing locations such as ##tag2##. Our ability to deliver excellent ##tag3## ##tag4## makes us stand out in the market. Call us today on 1800 249 000 for more information.');?></span></td>										
				</tr>
				<tr>
					<td><span ><?php _e('LPB META Keyword');?></span></td>
					<td><span ><?php _e('This value is the META keywords for the page. You can add general text here as well as ##Tag## values to fully make each page session keyword driven.');?></span></td>
					<td><span ><?php _e('##Tag1##, ##Tag2##, ##Tag3##, ##Tag4##, ##Tag5##');?></span></td>										
				</tr>
				<tr>
					<td><span ><?php _e('LPB Default Tag Values');?></span></td>
					<td><span ><?php _e('This value allows you to define TAG value defaults that are used if the page is navigated to with a link that does not contain any parameters. <br><br>These are important because you still want the page to show valid information in all possible user cases. <br><br>The number of values should equal the number of tags the page references. So if there are 3 tags, there would naturally be 3 defaults. Each tag parameters is delimited with double colon like this ::');?></span></td>
					<td><span ><?php _e('For 3 tags, this value could contain:<br>Legal::Services::Sydney <br><br> For 4 tags, this value could contain:<br>Legal::Services::Sydney::Guarenteed<br><br>Multi-word tags can be set like this: Legal-Services::Sydney-and-Metro::Amazing-Offer');?></span></td>										
				</tr>

			</tbody>
		</table>
	</div> 
	
	<h2>How To Setup Keyword Driven Page Content</h2>
	<div id="poststuff2" class="LPB-Instructions" >
		<p class="LPB-color"><?php _e( 'Keyword tags are easy to add to any page. All you need to do is replace the relevant keywords within your page with placeholders for tags.<br> So if you’d like your page to replace keywords with 3 tags, just insert ##tag1##, ##tag2## and ##tag3## wherever you want them to be shown, <br> including in the page ');  esc_html_e('<TITLE>'); _e(', Meta description, Meta keywords, inside the page body text. <br>Basically wherever you want to show your replaced keyword, insert a ##tag## specified for it.
' );?></p>
	</div>
	
	<h2>Some ##TAG## Specific That Are Helpful To Know</h2>
	<div id="poststuff3" class="LPB-Instructions" >
		<p class="LPB-color"><?php _e( 'Parameter tags can be manipulated to show in the correct case, either lower case, upper case or proper case as shown:' );?></p>
		
		<table class="LPB-table">
			<thead>
				<tr>
					<th class="LPB-th" style="text-align: left;"><?php _e( 'Tag Format' ); ?></th>
					<th class="LPB-th" style="text-align: left;"><?php _e( 'Example Parameter' );?></th>
					<th class="LPB-th" style="text-align: left;"><?php _e( 'Output Shown' );?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span ><?php _e('##tag1##');?></span></td>
					<td><span ><?php _e('legal-services ');?></span></td>
					<td><span ><?php _e('legal services');?></span></td>
				</tr> 
				<tr>
					<td><span ><?php _e('##Tag1##');?></span></td>
					<td><span ><?php _e('legal-services');?></span></td>
					<td><span ><?php _e('Legal services');?></span></td>										
				</tr>
				<tr>
					<td><span ><?php _e('##TAG1##');?></span></td>
					<td><span ><?php _e('legal-services');?></span></td>
					<td><span ><?php _e('LEGAL SERVICES');?></span></td>										
				</tr>
			</tbody>
		</table>
		<p class="LPB-color"><?php _e('By using tags in this way, you can control how they are shown within your text to ensure that they read well and are consistently formatting to match surrounding text.') ?></p>
	</div>
	
	<h2>Advanced Use For Image Manipulation</h2> 
	<div id="poststuff4" class="LPB-Instructions" >
		<p class="LPB-color"><?php _e( 'If you would like to show specific images on a page to match the keywords you are passing to it, <br>you can insert ##tag## placeholders inside image URLs with the objective of showing a specific image for that page.<br> Assume for example you have ##tag4## allocated to an image prefix with the value: ford, or holden, or audi. ' );?></p>
		<p class="LPB-color"> <?php _e( 'You can insert an image with a path like this: http://www.mydomain.com/images/##tag4##-front.jpg. <br>The outputs of this will be shown as follows:' );?></p>
		
		<table class="LPB-table">
			<thead>
				<tr>
					<th class="LPB-th" style="text-align: left;"><?php _e( '##tag4## Value Passed' ); ?></th>
					<th class="LPB-th" style="text-align: left;"><?php _e( 'Page Output' );?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span ><?php _e('ford');?></span></td>
					<td><span ><?php _e('http://www.mydomain.com/images/ford-front.jpg');?></span></td>
				</tr> 
				<tr>
					<td><span ><?php _e('holden');?></span></td>
					<td><span ><?php _e('http://www.mydomain.com/images/holden-front.jpg');?></span></td>
				</tr>
				<tr>
					<td><span ><?php _e('audi');?></span></td>
					<td><span ><?php _e('http://www.mydomain.com/images/audi-front.jpg');?></span></td>
				</tr>
			</tbody>
		</table>
		<p class="LPB-color"><?php _e( 'By manipulating image URLs in this way, you can completely control which images are shown based on matching parameter values that you require.' );?></p>
	</div>
	
</div> 
