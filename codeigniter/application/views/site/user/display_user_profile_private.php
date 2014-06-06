<?php
$this->load->view('site/templates/header',$this->data);
?>
<link rel="stylesheet" href="css/site/<?php echo SITE_COMMON_DEFINE ?>timeline.css" type="text/css" media="all"/>
<link rel="stylesheet" media="all" type="text/css" href="css/site/<?php echo SITE_COMMON_DEFINE ?>profile.css" />


<script src="js/site/<?php echo SITE_COMMON_DEFINE ?>profile.js"></script>

<!-- Section_start -->
<div class="lang-en wider no-subnav">
<div id="container-wrapper">
  <div class="container usersection">
    <div class="icon-cache"></div>
    <div id="tooltip"></div>
      <?php if($flash_data != '') { ?>
		<div class="errorContainer" id="<?php echo $flash_data_type;?>">
			<script>setTimeout("hideErrDiv('<?php echo $flash_data_type;?>')", 3000);</script>
			<p><span><?php echo $flash_data;?></span></p>
		</div>
		<?php } ?>
        <div class="wrapper timeline normal">
          <ul class="user-tab">
          </ul>
          <div class="no-result">
          	<b><?php if($this->lang->line('private_profile') != '') { echo stripslashes($this->lang->line('private_profile')); } else echo "This profile is private"; ?></b>
          </div>
        </div>
<?php
$this->load->view('site/templates/footer_menu');
$this->load->view('site/templates/footer');
?>