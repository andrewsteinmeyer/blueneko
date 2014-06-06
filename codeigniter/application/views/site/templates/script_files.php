<script type="text/javascript">
		var baseURL = '<?php echo base_url();?>';
		var BaseURL = '<?php echo base_url();?>';
		var likeTXT = '<?php echo addslashes(LIKE_BUTTON);?>';
		var likedTXT = '<?php echo addslashes(LIKED_BUTTON);?>';
		var unlikeTXT = '<?php echo addslashes(UNLIKE_BUTTON);?>';
		var currencySymbol = '<?php echo $currencySymbol;?>';
		var siteTitle = '<?php echo $siteTitle;?>';
		var LOGIN_SUCC_MSG = '<?php echo $login_succ_msg;?>';
		var can_show_signin_overlay = false;
		if (navigator.platform.indexOf('Win') != -1) {document.write("<style>::-webkit-scrollbar, ::-webkit-scrollbar-thumb {width:7px;height:7px;border-radius:4px;}::-webkit-scrollbar, ::-webkit-scrollbar-track-piece {background:transparent;}::-webkit-scrollbar-thumb {background:rgba(255,255,255,0.3);}:not(body)::-webkit-scrollbar-thumb {background:rgba(0,0,0,0.3);}::-webkit-scrollbar-button {display: none;}</style>");}
	</script>
<!--[if lt IE 9]>
<script src="js/site/html5shiv/dist/html5shiv.js"></script>
<![endif]-->
<script src="js/site/<?php echo SITE_COMMON_DEFINE ?>filescatalog.js" type="text/javascript"></script>
<script type="text/javascript" src="js/site/jquery-1.9.0.min.js"></script>
<script src="js/site/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="js/site/<?php echo SITE_COMMON_DEFINE ?>filesjquery-ui-1.js" type="text/javascript"></script>
<script src="js/site/<?php echo SITE_COMMON_DEFINE ?>filesjquery_002.js" type="text/javascript"></script>
<script src="js/site/<?php echo SITE_COMMON_DEFINE ?>filesjquery.js" type="text/javascript"></script>
<script src="js/site/main4.js" type="text/javascript"></script>
<script src="js/site/<?php echo SITE_COMMON_DEFINE ?>filestimeline_slideshow.js" type="text/javascript"></script>
<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/site/editor-config.js"></script>
<script src="js/validation.js" type="text/javascript"></script>
<script type="text/javascript">
function open_win(){
	window.open("<?php echo base_url();?>twtest/redirect");
	location.reload();
}

/*
 * Language Settings
 */
<?php if ($this->lang->line('shipping_add_ship')!=''){?> 
var lg_add_ship_addr = '<?php echo $this->lang->line('shipping_add_ship');?>';
<?php }else {?>
var lg_add_ship_addr = 'Add Shipping Address';
<?php }?>
<?php if ($this->lang->line('header_new_ship')!=''){?> 
var lg_new_ship_addr = '<?php echo $this->lang->line('header_new_ship');?>';
<?php }else {?>
var lg_new_ship_addr = 'New Shipping Address';
<?php }?>
<?php if ($this->lang->line('header_ships_wide')!=''){?> 
var lg_ships_wide = '<?php echo $this->lang->line('header_ships_wide');?>';
<?php }else {?>
var lg_ships_wide = 'We ships worldwide with global delivery services.';
<?php }?>
/*
 * ******************
 */
</script>