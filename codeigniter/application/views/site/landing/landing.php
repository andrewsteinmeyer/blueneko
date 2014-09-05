<?php
	$this->load->view('site/templates/header');
?>

<style>
.noproducts {
	float: left;
	width: 90%;
	padding: 5%;
	text-align: center;
	font-size: 25px;
	font-family: cursive;
}
</style>

<script>
	//toggle alert boxes for tracing spaghetti
	var DEBUG = false;
</script>

<script
	type="text/javascript" src="js/site/landing_category.js">
</script>

<!-- toggle category dropdown box to show/hide when you click it in the top-menu bar -->
<script type="text/javascript">
	function everythingView(val){
		if($('#everythinglist'+val).css('display')=='block'){
			$('#everythinglist'+val).hide('');
		}else{
			$('#everythinglist'+val).show('');
		}
	}
</script>

<link
	rel="stylesheet" type="text/css" media="all"
	href="css/site/<?php echo SITE_COMMON_DEFINE ?>timeline.css"
/>

<!-- Section_start -->
<section>
<div id="container-wrapper">
	<!-- get the control view from fc_control table -->
	<?php
		$controlview = $layoutList->result_array();

		if($controlview[0]['home_control'] == 'classic'){
			$viewhome = 'classic';
		}else if($controlview[0]['home_control'] == 'grid'){
			$viewhome = 'normal';
		}else if($controlview[0]['home_control'] == 'compact') {
			$viewhome = 'vertical';
		}

		$force_login = '';
		if($controlview[0]['popup_control'] == 'on' && $loginCheck==''){
			$force_login = 'force_login';
		}
	?>

	<div class="container timeline <?php echo $viewhome ; ?>">
	<!-- show errors if any exist -->
	<?php
		if($flash_data != '') {
			?>
			<div class="errorContainer" id="<?php echo $flash_data_type;?>">
				<!-- hide error container after 3 seconds -->
				<script>setTimeout("hideErrDiv('<?php echo $flash_data_type;?>')", 3000);</script>
				<p>
					<span><?php echo $flash_data;?> </span>
				</p>
			</div>
			<?php
		}
	?>

	<!-- show welcome message if not logged in, $loginCheck set in user controller -->
	<?php
		if ($loginCheck == ''){
			?>
			<!-- grab welcome messages from fc_layout table -->
			<p class="welcome">
				<strong>
				<?php
					foreach($layoutfulllist->result() as $layoutListRow){
						if($layoutListRow->place == 'welcome text'){
							echo  $layoutListRow->text;
							?>
				</strong>
				<br>
				<span>
							<?php
						}
						if($layoutListRow->place == 'welcome tag' ){
							echo  $layoutListRow->text;
							?>
				</span>
							<?php
						}
					}
				?>
			<?php
		}
	?>

		<!-- populate landing timeline if products exist -->
		<div class="wrapper-content landing_page">
		<?php
		//$productDetails loaded in landing controller in index() line 106
		//if products exist then load the top-menu bar with dropdown category list and viewer
		if (count($productDetails)>0){
			?>
			<!-- top menu bar -->
			<div class="top-menu">
				<!-- sort button in top menu bar -->
				<ul class="sorting">
				<?php if ($mainCategories->num_rows()>0){?>
					<li>
						<!-- start sort button off as "Everything", everythingView() function defined at top of this file -->
						<a href="javascript:everythingView('1');" class="current top-menu-btn">
							<?php if($this->lang->line('everything') != '') { echo stripslashes($this->lang->line('everything')); } else echo "Everything"; ?>
						</a>
						<!-- build dropdown list, populate with mainCategories, always starting with "Everything" -->
						<ul class="everything_list category" id="everythinglist1">
							<li>
								<!-- start with "Everything" category selected in dropdown list -->
								<a data-category="<?php echo base_url();?>" class="active">
									<?php if($this->lang->line('everything') != '') { echo stripslashes($this->lang->line('everything')); } else echo "Everything"; ?>
								</a>
							</li>

							<?php
								//populate the dropdown list with all remaining categories after "Everything"
								//click on each category link calls jquery (.sorting .category a) in landing_category.js line 5
								foreach ($mainCategories->result() as $row){
									if ($row->cat_name != ''){
										?>
										<li>
											<a data-category="<?php echo base_url().'?c='.$row->seourl;?>">
												<?php echo $row->cat_name;?>
											</a>
										</li>
										<?php
									}
								}
							?>
						</ul>
					</li>
				<?php } ?>
				</ul>

				<!-- build the viewer icons list in top menu bar so user can select Classic, Grid, or Compact view mode -->
				<!-- current" class is appended to button current view mode -->
				<!-- not sure what he is doing here with the onclic? adds class of 'classic' to timeline,
						 removes wrapper-content class which initially is "wrapper-content landing_page", and then adds it back just "wrapper-content"
						 I do not think onclic is working after inspecting elements -->
				<!-- jquery handles the click of these btns around line 741 below -->
				<div class="viewer">
					<ul>
						<li class="classic"><a href="#"
						<?php if($viewhome == 'classic'){ ?>
							class="current"
							onclic="$('.container.timeline').addClass('classic').find('.wrapper-content').removeAttr('class').addClass('wrapper-content');"
							<?php } ?>><i class="ic-view4"></i> <span><?php if($this->lang->line('landing_classig_view') != '') { echo stripslashes($this->lang->line('landing_classig_view')); } else echo "Classic View"; ?><b></b>
							</span> </a></li>
						<li class="normal"><a href="#"
						<?php if($viewhome == 'normal'){ ?>
							class="current"
							onclic="$('.container.timeline').addClass('classic').find('.wrapper-content').removeAttr('class').addClass('wrapper-content');"
							<?php } ?>><i class="ic-view2"></i> <span><?php if($this->lang->line('user_grid_view') != '') { echo stripslashes($this->lang->line('user_grid_view')); } else echo "Grid View"; ?><b></b>
							</span> </a></li>
						<li class="vertical"><a href="#"
						<?php if($viewhome == 'vertical'){ ?>
							class="current"
							onclic="$('.container.timeline').addClass('classic').find('.wrapper-content').removeAttr('class').addClass('wrapper-content');"
							<?php } ?>><i class="ic-view3"></i> <span><?php if($this->lang->line('user_compact_view') != '') { echo stripslashes($this->lang->line('user_compact_view')); } else echo "Compact View"; ?><b></b>
							</span> </a></li>
					</ul>
				</div>
			</div>
			<?php

			//loaded in the landing controller in index() line 106
			//includes all products from sellers and affiliates
			$productArr = $productDetails;

			?>
			<div id="content">
				<!-- stream loads the pictures of the products -->
				<ol class="stream">
				<?php
				//load the stream pictures in blocks of 3
				//not sure why he does 3 exactly
				for ($i=0;$i<count($productArr);$i=$i+3){
					if (isset($productArr[$i]->id)){
						//grab the first image for the product
						//use dummyImage if no image exists
						$imgArr = explode(',', $productArr[$i]->image);
						$img = 'dummyProductImage.jpg';
						foreach ($imgArr as $imgVal){
							if ($imgVal != ''){
								$img = $imgVal;
								break;
							}
						}
						$fancyClass = 'fancy';
						$fancyText = LIKE_BUTTON;
						//iterate over $likedProducts for this user
						//loaded in landing controller line 37 from fc_product_likes table
						//change class from fancy to fancy'd and like to liked if user has already liked it
						if (count($likedProducts)>0 && $likedProducts->num_rows()>0){
							foreach ($likedProducts->result() as $likeProRow){
								if ($likeProRow->product_id == $productArr[$i]->seller_product_id){
									$fancyClass = 'fancyd';$fancyText = LIKED_BUTTON;break;
								}
							}
						}

						//if there is a web_link use that, otherwise use product link
						if (isset($productArr[$i]->web_link)){
							$prodLink = "user/".$productArr[$i]->user_name."/things/".$productArr[$i]->seller_product_id."/".url_title($productArr[$i]->product_name,'-');
						}else {
							$prodLink = "things/".$productArr[$i]->id."/".url_title($productArr[$i]->product_name,'-');
						}
						?>
					<!-- load the first image in the group of 3 -->
					<li class="big clear"
						tid="<?php echo $productArr[$i]->seller_product_id;?>"
						tuserid="<?php echo $productArr[$i]->user_id;?>">
						<div class="figure-item">
							<!-- span class="pre"></span -->
							<!-- load user picture for classic view, not displayed in grid and vertical view -->
							<a
								href="<?php if ($productArr[$i]->user_id != '0'){echo base_url().'user/'.$productArr[$i]->user_name;}else {echo base_url().'user/administrator';}?>"
								class="vcard <?php echo $force_login;?>">
									<?php if ($productArr[$i]->thumbnail == ''){?>
										<img src="images/users/user-thumb1.png">
									<?php }else {?>
										<img src="images/users/<?php echo $productArr[$i]->thumbnail;?>">
									<?php }?>
							</a>
							<!-- load link to product and setup product image settings for each possible view -->
							<a href="<?php echo $prodLink;?>"
								class="figure-img <?php echo $force_login;?>">
								<span
									class="figure grid"
									style="background-size: cover"
									data-ori-url="images/product/<?php echo $img;?>"
									data-310-url="images/product/<?php echo $img;?>">
									<em class="back"></em>
								</span>
								<span
									class="figure classic">
									<em class="back"></em>
									<img src="images/product/<?php echo $img;?>"
											data-width="640"
											data-height="640">
								</span>
								<span
									class="figure vertical">
									<em class="back"></em>
									<img src="images/product/<?php echo $img;?>"
										data-width="310"
										data-height="310">
								</span>
								<!-- load product name for classic and grid view, not displayed in vertical -->
								<span
									class="figcaption"><?php echo $productArr[$i]->product_name;?>
								</span>
							</a>
							<!-- load price and seller detail for product -->
							<!-- displayed differently for each view -->
							<em class="figure-detail">
								<?php if (!isset($productArr[$i]->web_link)){?>
									<span class="price"><?php echo $currencySymbol;?> <?php echo $productArr[$i]->sale_price;?>
										<small><?php echo $currencyType;?> </small>
									</span>
								<?php }?>
								<span
									class="username">
									<em style="padding-left: 0;">
										<i>
											<?php if($this->lang->line('user_by') != '') { echo stripslashes($this->lang->line('user_by')); } else echo "by"; ?>
										</i>
										<a class="<?php echo $force_login;?>"
											href="<?php if ($productArr[$i]->user_id != '0'){echo base_url().'user/'.$productArr[$i]->user_name;}else {echo base_url().'user/administrator';}?>">
														<?php if ($productArr[$i]->user_id != '0'){echo $productArr[$i]->full_name;}else {echo 'administrator';}?>
										</a> +
										<?php echo $productArr[$i]->likes;?>
									</em>
								</span>
							</em>
							<!-- load list of functions including "Add to List", "Comment", "Share", and "View Comment" -->
							<!-- each view displays different combinations of the four groups -->
							<ul class="function">
								<li class="list"><a href="#">Add to List</a></li>
								<li class="cmt"><a href="#">Comment</a></li>
								<li class="share">
									<button type="button"
										<?php if ($loginCheck==''){?>
											require_login="true"
										<?php }?>
										data-timage="<?php //echo base_url();?>images/product/<?php echo $img;?>"
										class="btn-share"
										tname="<?php echo $productArr[$i]->product_name;?>"
										username="<?php if ($productArr[$i]->user_id != '0'){echo $productArr[$i]->full_name;}else {echo 'administrator';}?>">
										<i class="ic-share"></i>
									</button>
								</li>
								<li class="view-cmt"><a href="#">5 comments</a></li>
							</ul>
							<!-- load button to like the product -->
							<a href="#" item_img_url="images/product/<?php echo $img;?>"
								tid="<?php echo $productArr[$i]->seller_product_id;?>"
								class="button <?php echo $fancyClass;?>"
								<?php if ($loginCheck==''){?> require_login="true" <?php }?>>
								<span>
									<i></i>
								</span>
								<?php echo $fancyText;?>
							</a>
						</div>
					</li>
					<?php
					}
					//if a second product exists in the list
					//then setup image, buttons, etc
					if (isset($productArr[$i+1]->id)){
						$imgArr = explode(',', $productArr[$i+1]->image);
						$img = 'dummyProductImage.jpg';
						foreach ($imgArr as $imgVal){
							if ($imgVal != ''){
								$img = $imgVal;
								break;
							}
						}
						$fancyClass = 'fancy';
						$fancyText = LIKE_BUTTON;
						//iterate over $likedProducts for this user for second time
						//this is redundant, already did this for first product
						//loaded in landing controller lin 37 from fc_product_likes table
						//change class from fancy to fancy'd and like to liked if user has already liked it
						if (count($likedProducts)>0 && $likedProducts->num_rows()>0){
							foreach ($likedProducts->result() as $likeProRow){
								if ($likeProRow->product_id == $productArr[$i+1]->seller_product_id){
									$fancyClass = 'fancyd';$fancyText = LIKED_BUTTON;break;
								}
							}
						}

						if (isset($productArr[$i+1]->web_link)){
							$prodLink = "user/".$productArr[$i+1]->user_name."/things/".$productArr[$i+1]->seller_product_id."/".url_title($productArr[$i+1]->product_name,'-');
						}else {
							$prodLink = "things/".$productArr[$i+1]->id."/".url_title($productArr[$i+1]->product_name,'-');
						}
						?>
					<!-- load the second image in the group of 3 -->
					<li class="mid clear"
						tid="<?php echo $productArr[$i+1]->seller_product_id;?>"
						tuserid="<?php echo $productArr[$i+1]->user_id;?>">
						<div class="figure-item">
							<!-- span class="pre"></span -->
							<a
								href="<?php if ($productArr[$i+1]->user_id != '0'){echo 'user/'.$productArr[$i+1]->user_name;}else {echo base_url().'user/administrator';}?>"
								class="vcard <?php echo $force_login;?>">
									<?php if ($productArr[$i+1]->thumbnail == ''){?>
										<img src="images/users/user-thumb1.png">
									<?php }else {?>
										<img src="images/users/<?php echo $productArr[$i+1]->thumbnail;?>">
									<?php }?>
							</a>
							<a href="<?php echo $prodLink;?>"
								class="figure-img <?php echo $force_login;?>">
								<span
									class="figure grid" style="background-size: cover"
									data-ori-url="images/product/<?php echo $img;?>"
									data-310-url="images/product/<?php echo $img;?>">
									<em class="back"></em>
								</span>
								<span
									class="figure classic">
									<em class="back"></em>
									<img src="images/product/<?php echo $img;?>"
										data-width="310"
										data-height="310">
									</span>
								<span
									class="figure vertical">
									<em class="back"></em>
									<img src="images/product/<?php echo $img;?>"
										data-width="310"
										data-height="310">
								</span>
								<span
									class="figcaption"><?php echo $productArr[$i+1]->product_name;?>
								</span>
							</a>
							<em class="figure-detail">
								<?php if (!isset($productArr[$i+1]->web_link)){?>
									<span class="price"><?php echo $currencySymbol;?> <?php echo $productArr[$i+1]->sale_price;?>
										<small><?php echo $currencyType;?> </small>
									</span>
								<?php }?>
								<span
									class="username">
									<em style="padding-left: 0;">
										<i>
											<?php if($this->lang->line('user_by') != '') { echo stripslashes($this->lang->line('user_by')); } else echo "by"; ?>
										</i>
										<a class="<?php echo $force_login;?>"
											href="<?php if ($productArr[$i+1]->user_id != '0'){echo base_url().'user/'.$productArr[$i+1]->user_name;}else {echo base_url().'user/administrator';}?>">
														<?php if ($productArr[$i+1]->user_id != '0'){echo $productArr[$i+1]->full_name;}else {echo 'administrator';}?>
										</a> +
										<?php echo $productArr[$i+1]->likes;?>
									</em>
								</span>
							</em>
							<ul class="function">
								<li class="list"><a href="#">Add to List</a></li>
								<li class="cmt"><a href="#">Comment</a></li>
								<li class="share">
									<button type="button" <?php if ($loginCheck==''){?>
										require_login="true" <?php }?>
										data-timage="<?php //echo base_url();?>images/product/<?php echo $img;?>"
										class="btn-share"
										tname="<?php echo $productArr[$i+1]->product_name;?>"
										username="<?php if ($productArr[$i+1]->user_id != '0'){echo $productArr[$i+1]->full_name;}else {echo 'administrator';}?>">
										<i class="ic-share"></i>
									</button>
								</li>
								<li class="view-cmt"><a href="#">5 comments</a></li>
							</ul>
							<a href="#" item_img_url="images/product/<?php echo $img;?>"
								tid="<?php echo $productArr[$i+1]->seller_product_id;?>"
								class="button <?php  echo $fancyClass;?>"
								<?php if ($loginCheck==''){?> require_login="true" <?php }?>>
								<span>
									<i></i>
								</span>
								<?php echo $fancyText;?>
							</a>
						</div>
					</li>
					<?php
					}
					//if a third product exists in the list
					//then setup image, buttons, etc
					if (isset($productArr[$i+2]->id)){
						$imgArr = explode(',', $productArr[$i+2]->image);
						$img = 'dummyProductImage.jpg';
						foreach ($imgArr as $imgVal){
							if ($imgVal != ''){
								$img = $imgVal;
								break;
							}
						}
						$fancyClass = 'fancy';
						$fancyText = LIKE_BUTTON;
						//iterate over $likedProducts for this user again for third time
						//this is redundant, already did this for first and second product above
						//loaded in landing controller lin 37 from fc_product_likes table
						//change class from fancy to fancy'd and like to liked if user has already liked it
						if (count($likedProducts)>0 && $likedProducts->num_rows()>0){
							foreach ($likedProducts->result() as $likeProRow){
								if ($likeProRow->product_id == $productArr[$i+2]->seller_product_id){
									$fancyClass = 'fancyd';$fancyText = LIKED_BUTTON;break;
								}
							}
						}
						if (isset($productArr[$i+2]->web_link)){
							$prodLink = "user/".$productArr[$i+2]->user_name."/things/".$productArr[$i+2]->seller_product_id."/".url_title($productArr[$i+2]->product_name,'-');
						}else {
							$prodLink = "things/".$productArr[$i+2]->id."/".url_title($productArr[$i+2]->product_name,'-');
						}
						?>
					<!-- load the third image in the group of 3 -->
					<li class="mid "
						tid="<?php echo $productArr[$i+2]->seller_product_id;?>"
						tuserid="<?php echo $productArr[$i+2]->user_id;?>">
						<div class="figure-item">
							<!-- span class="pre"></span -->
							<a href="<?php echo $prodLink;?>"
								class="figure-img <?php echo $force_login;?>">
								<span
									class="figure grid"
									style="background-size: cover"
									data-ori-url="images/product/<?php echo $img;?>"
									data-310-url="images/product/<?php echo $img;?>">
									<em class="back"></em>
								</span>
								<span
									class="figure classic">
									<em class="back"></em>
									<img src="images/product/<?php echo $img;?>"
										data-width="310"
										data-height="310">
								</span>
								<span
									class="figure vertical">
									<em class="back"></em>
									<img src="images/product/<?php echo $img;?>"
										data-width="310"
										data-height="310">
								</span>
								<span
									class="figcaption"><?php echo $productArr[$i+2]->product_name;?>
								</span>
							</a>
							<em class="figure-detail">
								<?php if (!isset($productArr[$i+2]->web_link)){?>
									<span class="price"><?php echo $currencySymbol;?> <?php echo $productArr[$i+2]->sale_price;?>
										<small><?php echo $currencyType;?> </small>
									</span>
								<?php }?>
								<span
									class="username">
									<em style="padding-left: 0;">
										<i>
											<?php if($this->lang->line('user_by') != '') { echo stripslashes($this->lang->line('user_by')); } else echo "by"; ?>
										</i>
										<a class="<?php echo $force_login;?>"
											href="<?php if ($productArr[$i+2]->user_id != '0'){echo base_url().'user/'.$productArr[$i+2]->user_name;}else {echo base_url().'user/administrator';}?>">
														<?php if ($productArr[$i+2]->user_id != '0'){echo $productArr[$i+2]->full_name;}else {echo 'administrator';}?>
										</a> +
										<?php echo $productArr[$i+2]->likes;?>
									</em>
								</span>
							</em>
							<ul class="function">
								<li class="list"><a href="#">Add to List</a></li>
								<li class="cmt"><a href="#">Comment</a></li>
								<li class="share">
									<button type="button" <?php if ($loginCheck==''){?>
										require_login="true" <?php }?>
										data-timage="<?php //echo base_url();?>images/product/<?php echo $img;?>"
										class="btn-share"
										tname="<?php echo $productArr[$i+2]->product_name;?>"
										username="<?php if ($productArr[$i+2]->user_id != '0'){echo $productArr[$i+2]->full_name;}else {echo 'administrator';}?>">
										<i class="ic-share"></i>
									</button>
								</li>
								<li class="view-cmt"><a href="#">5 comments</a></li>
							</ul>
							<a href="#" item_img_url="images/product/<?php echo $img;?>"
								tid="<?php echo $productArr[$i+2]->seller_product_id;?>"
								class="button <?php echo $fancyClass;?>"
								<?php if ($loginCheck==''){?> require_login="true" <?php }?>>
								<span>
									<i></i>
								</span>
								<?php echo $fancyText;?>
							</a>
						</div>
					</li>
					<?php
					}
				}
				?>
				</ol>
				<!-- load the infinate scrolling div -->
				<div id="infscr-loading" style="display: none;">
					<!--img alt='Loading...' src="/_ui/images/site/common/ajax-loader.gif"-->
					<span class="loading">Loading...</span>
				</div>
				<!-- load the pagination div -->
				<!-- $paginationDisplay set in landing controller line 81 -->
				<div class="pagination" style="display: none">
				<?php if ($force_login != 'force_login'){echo $paginationDisplay;} ?>
				</div>
			</div>

			<?php
		}else {
			?>
			<!-- no products exist, display "No products available" -->
			<div id="content">
				<!-- noproducts style set in line 5 -->
				<p class="noproducts">
				<?php if($this->lang->line('product_not_avail') != '') { echo stripslashes($this->lang->line('product_not_avail')); } else echo "No products available"; ?>
				</p>
				<?php }?>
				<!-- load footer -->
				<?php
				$this->load->view('site/templates/footer_menu');
				?>
				<style>
				<!--
		    .timeline #footer {
			    position: relative;
			    width: 98%;
			    bottom: 0;
			    padding: 0 10px;
		    }

		    .timeline #footer ul.footer-nav {
			    text-align: left;
			    line-height: 1.6;
			    padding: 0;
		    }
		    -->
        </style>
			</div>
			<!-- scroll to top button -->
			<a href="#header" id="scroll-to-top">
				<span>
					<?php if($this->lang->line('signup_jump_top') != '') { echo stripslashes($this->lang->line('signup_jump_top')); } else echo "Jump to top"; ?>
				</span>
			</a>
		</div>
		<!-- / container -->
	</div>
</div>
</section>

<!-- Section_start -->
<script>
jQuery(function($){
	$('a.more').mouseover(function(){$('.sns-minor').show();return false;});
	$('a.more').click(function(){
		$('.sns-minor').toggleClass('toggle');
	});
	$('.sns-minor .trick').click(function(){
		$('.sns-minor').removeClass('toggle');
		return false;
	});
	$('.sns-major').mouseover(function(){$('.sns-minor').hide();return false;});
	$('.sns-minor').mouseover(function(){if ($(this).hasClass('toggle')==false) $(this).hide();});
});
</script>

<script>
(function(){
	var $btns = $('.viewer li'), $stream = $('ol.stream'), $container=$('.container'), $wrapper = $('.wrapper-content'), first_id = 'stream-first-item_', latest_id = 'stream-latest-item_';
	$stream.data('feed-url', '/user-stream-updates?new-timeline&feed=featured');

	// show images as each image is loaded
	//initially triggered below in line 729
	$stream.on('itemloaded', function(){
		if (DEBUG == true) { alert('landing.php: itemloaded'); }

		var $latest = $stream.find('>#'+latest_id).removeAttr('id'),
	 	    $first = $stream.find('>#'+first_id).removeAttr('id'),
		    $target=$(), viewMode;

		// merge sameuser thing
		// see if any adjacent products are from same seller
		// remove "clear" class from the same seller's next products??
		var userid = $latest.attr('tuserid');
		var $currents = $latest.prevUntil('li[tuserid!='+userid+"]");
		var $nexts = $latest.nextUntil('li[tuserid!='+userid+"]");
		var $group = $($currents).add($latest).add($nexts);
		$nexts.filter(".clear").removeClass("clear").find("a.vcard").detach();
		if($group.length>2){
/*			$group.removeClass("big mid").addClass("sm").each(function(i){
			if(i%3==0) $(this).addClass("clear");
			});
			*/
			if($group.length%3==2){
//				$group.last().removeClass("sm").addClass("mid").prev().removeClass("sm").addClass("mid");
			}else if($group.length%3==1){
//				$group.last().removeClass("sm").addClass("big");
			}
		}else if($group.length==2){
//				$group.removeClass("big").addClass("mid");
		}

		//start $forceRefresh as "false"
		var forceRefresh = false;

		//target the new items
		//if first time, all items are new
		if(!$first.length || !$latest.length) {
			$target = $stream.children('li');
		} else {
			//newThings are all li elements before the stream-first-item_ and after stream-latest-item_
			//if items before stream-first-item_ then forceRefresh
			var newThings = $first.prevAll('li');
			if(newThings.length) forceRefresh = true;
			$target = newThings.add($latest.nextAll('li'));
		}

		//set modifiers on first and last list elements in stream
		$stream.find('>li:first-child').attr('id', first_id);
		$stream.find('>li:last-child').attr('id', latest_id);

		//container view is set at top of file line 42
		//pulled from fc_control table
	  viewMode = $container.hasClass('vertical') ? 'vertical' : ($container.hasClass('normal') ? 'grid':'classic');

	  //setup grid view
	  //set grid span's background image url using css
	  //image url dependant on height of grid span
		if(viewMode=='grid'){
			$target.each(function(i,v,a){
				var $li = $(this), src_g;
				var $grid_img = $li.find(".figure.grid");

				//set background image for products in grid mode
				//if the "figure grid" span is greather than 400, use the data-ori-url
				//else, use the data-310-url
				if($grid_img.height()>400){
					$grid_img.css("background-image", "url("+$grid_img.attr("data-ori-url")+")");
				}else{
					$grid_img.css("background-image", "url("+$grid_img.attr("data-310-url")+")");
				}
			});
		}

		//setup vertical view
		//show the infinite scrolling
		//arrange the products in the stream
		if(viewMode == 'vertical'){
			if (DEBUG == true ) {alert('landing.php onitemloaded: if view is vertical, set timeout to arrange'); }
			//shows "Loading.." while arranging
			$('#infscr-loading').show();
			setTimeout(function(){
				if (DEBUG == true) { alert('landing.php onitemloaded: timeout expired, calling arrange in landing.php'); }
				arrange(forceRefresh);
				//hide "Loading.." after arranging
				$('#infscr-loading').hide();
			},10);
		}

	});

	//trigger 'itemloaded' on stream to load products
	//'itemloaded' event listener starts above on line 628
	$stream.trigger('itemloaded');

	//set css on "tip" buttons
	//bts are the list elements in the viewer div
	//viewer is in top menu bar where user selects view mode "Classic, Vertical, or Grid"
	$btns.each(function(){
		var $tip = $(this).find('span');
		$tip.css('margin-left', -$tip.width()/2 - 8 + 'px');
	});

	//clicking the view buttons adds "current" class
	//button triggers "setView" and passes "normal, vertical, or classic"
	//setView defined below around line 753
	$btns.click(function(event){
		event.preventDefault();
		if($wrapper.hasClass('anim')) return;

		var $btn = $(this);

		// highlight this button only
		// remove "current" from class of other buttons
		// put "current" on button that has been clicked
		$btns.find('a.current').removeClass('current');
		$btn.find('a').addClass('current');

		//grab the class on the button that is clicked
		//set the view to that particular class
		if(/\b(normal|vertical|classic)\b/.test($btn.attr('class'))){
			setView(RegExp.$1);
		}
	});

	//register redraw event
	$wrapper.on('redraw', function(event){
		var curMode = '';
		if(/\b(normal|vertical|classic)\b/.test($container.attr('class'))) curMode = RegExp.$1;
		if(curMode) setView(curMode, true);
	});

	//called when user clicks button in viewer div in top-menu
	//mode is "vertical, normal, or classic"
	function setView(mode, force){
		if (DEBUG ==true) { alert('landing.php: setView()'); }
		//return if this view is already currently set
		if(!force && $container.hasClass(mode)) return;
		var $items = $stream.find('>li');

		//only load 100
		if($items.length>100){
			$items.filter(":eq(100)").nextAll().detach();
		}

		//if window does not have Modernizr
		//cannot do fancy fade so need to set opacity to 1 and trigger after-fadein
		//after-fadein event calls done()
		if(!window.Modernizr || !Modernizr.csstransitions ){
			$stream.addClass('loading');
			//not sure where before-fadeout event is registered
			$wrapper.trigger('before-fadeout');
			$stream.removeClass('loading');
			//before-fadein is registered in line 1163, calls swapContent()
			$wrapper.trigger('before-fadein');
			switchTo(mode);

			//set grid background image url
			//image depends on height of listing element
			if(mode=='normal'){
				$items.each(function(i,v,a){
					var $li = $(this);
					var $grid_img = $li.find(".figure.grid");

					if($li.height()>400){
						$grid_img.css("background-image", "url("+$grid_img.attr("data-ori-url")+")");
					}else{
						$grid_img.css("background-image", "url("+$grid_img.attr("data-310-url")+")");
					}
				});
			}

			//set opacity to viewable and trigger after-fadein
			//after-fadein is registered in line 1148
			//after-fadein calls done()
			$stream.find('>li').css('opacity',1);
			$wrapper.trigger('after-fadein');
			return;
		}

		//don't see before-fadeout registered anywhere yet
		//add "anim" class to wrapper and "loading" class to stream
		$wrapper.trigger('before-fadeout').addClass('anim');
		$stream.addClass('loading');
		var item,
		    $visibles, visibles = [], prevVisibles, thefirst,
		    offsetTop = $stream.offset().top,
		    hh = $('#header-new').height(),
		    sc = $(window).scrollTop(),
		    wh = $(window).innerHeight(),
			f_right, f_bottom, v_right, v_bottom,
			i, c, v, d, animated = 0;

		// get visible elements
		for(i=0,c=$items.length; i < c; i++){
			item = $items[i];
			// think he is checking if item is within the view
			// he tried hiding the out of view elements, but they are commented out?
			if (offsetTop + item.offsetTop + item.offsetHeight < sc + hh) {
				//item.style.visibility = 'hidden';
			} else if (offsetTop + item.offsetTop > sc + wh) {
				//item.style.visibility = 'hidden';
				break;
			} else {
				//add visible items to array
				visibles[visibles.length] = item;
			}
		}
		prevVisibles = visibles;

		// get the first animated element
		for(i=0,c=Math.min(visibles.length,10),thefirst=null; i < c; i++){
			v = visibles[i];

			//find top and leftmost item in the list
			//that should be first element in stream
			if( !thefirst || (thefirst.offsetLeft > v.offsetLeft) || (thefirst.offsetLeft == v.offsetLeft && thefirst.offsetTop > v.offsetTop) ) {
				thefirst = v;
			}
		}

		//skip fadeout and go ahead and fade in if no visible products
		if(visibles.length==0) fadeIn();

		// fade out elements using delay based on the distance between each element and the first element.
		for(i=0,c=visibles.length; i < c; i++){
			v = visibles[i];

			//set timeout delay for opacity on the element
			//setting opacity to 0 to fade out
			d = Math.sqrt(Math.pow((v.offsetLeft - thefirst.offsetLeft),2) + Math.pow(Math.max(v.offsetTop-thefirst.offsetTop,0),2));
			delayOpacity(v, 0, d/5);

			//if we have reached the last product in list
			//then setup fadeIn
			if(i == c -1){
				setTimeout(fadeIn,300+d/5);
			}
		}

		function fadeIn(){
			if (DEBUG == true) {alert('landing.php fadein(): called from landing.php setView'); }
			$wrapper.trigger('before-fadein');

			if($wrapper.hasClass("wait")){
				setTimeout(fadeIn, 50);
				return;
			}

			var i, c, v, thefirst, COL_COUNT, visibles = [], item;

			if($items.length !== $stream.get(0).childNodes.length || $items.get(0).parentNode !== $stream.get(0)) $items = $stream.find('>li');
			$stream.height($stream.parent().height());

			switchTo(mode);

			if(mode=='normal'){
				$items.each(function(i,v,a){
					var $li = $(this);
					var $grid_img = $li.find(".figure.grid");

					if($li.height()>400){
						$grid_img.css("background-image", "url("+$grid_img.attr("data-ori-url")+")");
					}else{
						$grid_img.css("background-image", "url("+$grid_img.attr("data-310-url")+")");
					}
				});
			}

			//remove "loading" class from stream and remove "anim" class from wrapper
			$stream.removeClass('loading');
			$wrapper.removeClass('anim');

			// get visible elements
			for(i=0,c=$items.length; i < c; i++){
				item = $items[i];
				if (offsetTop + item.offsetTop + item.offsetHeight < sc + hh) {
					//item.style.visibility = 'hidden';
				} else if (offsetTop + item.offsetTop > sc + wh) {
					//item.style.visibility = 'hidden';
					break;
				} else {
					visibles[visibles.length] = item;
					item.style.opacity = 0;
				}
			}

			$wrapper.addClass('anim');

			$(visibles).css({opacity:0,visibility:''});
			COL_COUNT = Math.floor($stream.width()/$(visibles[0]).width());

			// get the first animated element
			for(i=0,c=Math.min(visibles.length,COL_COUNT),thefirst=null; i < c; i++){
				v = visibles[i];

				if( !thefirst || (thefirst.offsetLeft > v.offsetLeft) || (thefirst.offsetLeft == v.offsetLeft && thefirst.offsetTop > v.offsetTop) ) {
					thefirst = v;
				}
			}

			// fade in elements using delay based on the distance between each element and the first element.
			if(visibles.length==0) done();
			for(i=0,c=visibles.length; i < c; i++){
				v = visibles[i];

				d = Math.sqrt(Math.pow((v.offsetLeft - thefirst.offsetLeft),2) + Math.pow(Math.max(v.offsetTop-thefirst.offsetTop,0),2));
				delayOpacity(v, 1, d/5);

				if(i == c -1) setTimeout(done, 300+d/5);
			}
		};

		function done(){
			if (DEBUG == true) { alert("landing.php done(): called from landing.php fadein"); }
			$wrapper.removeClass('anim');
			/*if(prevVisibles && prevVisibles.length) {
				for(var i=0,c=visibles.length; i < c; i++){
					if(visibles[i].style.opacity == '0') visibles[i].style.opacity = 1;
				}
			}*/
			$stream.find('>li').css('opacity',1);
			$wrapper.trigger('after-fadein');
		};

		function delayOpacity(element, opacity, interval){
			setTimeout(function(){ element.style.opacity = opacity }, Math.floor(interval));
		};

		//sets "vertical, normal, or classic" to "container timeline"
		//calls arrange to organize product pictures
		function switchTo(mode){
			if (DEBUG == true) { alert("landing.php switchTo(): called from landing.php fadein"); }
			var currentMode = $container.hasClass('vertical')?'vertical':($container.hasClass('classic')?'classic':'normal')
			$container.removeClass('vertical normal classic').addClass(mode);
			if(mode == 'vertical') {
				arrange(true);
				$.infiniteshow.option('prepare',2000);
			} else {
				$stream.css('height','');
				$.infiniteshow.option('prepare',4000);
			}
			if($.browser.msie) $.infiniteshow.option('prepare',1000);
			$.cookie.set('timeline-view',mode,9999);
		};

	};
	var bottoms = [0,0,0,0];

	//force_refresh is passed but overridden as "true" anyway line 942
	function arrange(force_refresh){
		if (DEBUG == true) { alert('landing.php: arrange()'); }

		var i, c, x, w, h, nh, min, $target, $marker, $first, $img, COL_COUNT, ITEM_WIDTH;

		var ts = new Date().getTime();

		$marker = $stream.find('li.page_marker_');
		force_refresh = true;

		//force_refresh is always true here
		//he sets it true above, so $marker.length is never checked?
		if(force_refresh || !$marker.length) {
			force_refresh = true;
			bottoms = [0,0,0,0];
			$target = $stream.children('li');
		} else {
			$target = $marker.nextAll('li');
		}
		if(!$target.length) return;

		$first = $target.eq(0);
		$target.eq(-1).addClass('page_marker_');
		$marker.removeClass('page_marker_');

		//ITEM_WIDTH  = parseInt($first.width());
		//COL_COUNT   = Math.floor($stream.width()/ITEM_WIDTH);
		ITEM_WIDTH = 230;
		COL_COUNT = 4;

		//iterate over the list elements in the stream
		//each list item is a product
		//set the top and left css depending upon the item location in list
		for(i=0,c=$target.length; i < c; i++){
			min = Math.min.apply(Math, bottoms);

			for(x=0; x < COL_COUNT; x++) if(bottoms[x] == min) break;

			//$li = $target.eq(i);
			$li = $($target[i]);
			$img = $li.find('.figure.vertical > img');
			if(!(nh = $img.attr('data-calcHeight'))){
				w = +$img.attr('data-width');
				h = +$img.attr('data-height');

				if(w && h) {
					//nh = $img.width()/w * h;
					nh = 210/w * h;
					nh = Math.max(nh,150);
					$img.attr('height', nh).data('calcHeight', nh);
				}else{
					nh = $img.height();
				}
			}

			$li.css({top:bottoms[x], left:x*ITEM_WIDTH});
			bottoms[x] = bottoms[x] + nh + 20;
		}

		//set the height of the stream
		//depends on how many rows of products
		$stream.height(Math.max.apply(Math, bottoms));

	};

	//register event listener for "arrange"
	$wrapper.on('arrange', function(){ arrange(true); });

	//set up click listener for notibar
	//when clicked, delete info from jStorage and trigger 'itemloaded'
	//then, set product image url if grid mode
	$notibar = $('.new-content');
	$notibar.off('click').on('click', function(){
		setTimeout(function(){
		    $.jStorage.deleteKey("fancy.prefetch.stream");
		    $.jStorage.deleteKey("first-featured");
		    $.jStorage.deleteKey("first-all");
		    $.jStorage.deleteKey("first-following");
			$stream.trigger('itemloaded');

			//if normal mode (aka grid mode)
			//then set the background image url based upon the span height
			if( $container.hasClass("normal") ){
				$stream.find("li").each(function(i,v,a){
					var $li = $(this), src_g;
					var $grid_img = $li.find(".figure.grid");

					if($grid_img.height()>400){
						$grid_img.css("background-image", "url("+$grid_img.attr("data-ori-url")+")");
					}else{
						$grid_img.css("background-image", "url("+$grid_img.attr("data-310-url")+")");
					}
				});
			}
		},100);
	});

	// feed selection
	var $feedtabs = $('.sorting a[data-feed]');
	var init_ts = $stream.attr("ts");
	var ttl  = 5 * 60 * 1000;

	$feedtabs.click(function(e){
		var tab = $(e.target).data("feed")||"featured";
		switchTab(tab);
		e.preventDefault();
	});
	function switchTab(tab){
		$.jStorage.deleteKey("fancy.prefetch.stream");
		$feedtabs.removeClass("current");
		var $currentTab = $feedtabs.filter("a[data-feed="+tab+"]").addClass("current");
		$url = $('a.btn-more').hide();
		$win = $(window);

		var result = null;
		$wrapper.addClass("wait");
		// hide notibar if it showing
		$notibar.hide();
		$stream.attr('ts','').data('feed-url', '/user-stream-updates?new-timeline&feed='+tab);
		var loc = tab;
		var keys = {
			timestamp : 'fancy.home-new.timestamp.'+loc,
			stream  : 'fancy.home-new.stream.'+loc,
			latest  : 'fancy.home-new.latest.'+loc,
			nextURL : 'fancy.home-new.nexturl.'+loc
		};

		if(!(result=$.jStorage.get('first-'+tab))){
			$.ajax({
				url : '/?new-timeline&feed='+tab,
				dataType : 'html',
				success : function(data, st, xhr) {
					result = data;
					$.jStorage.set('first-'+tab, result, {TTL:5*60*1000});
				},
				error : function(xhr, st, err) {
					url = '';
				},
				complete : function(){
				}
			});
		}

		var swapContent = function(){
			alert('here swap before');
			if(!result){
				setTimeout(swapContent,50);
				return;
			}
			alert('here swap after');

			if($wrapper.hasClass("swapping")) return;
			$wrapper.addClass("swapping");
			$stream.find(">li").detach();

			$container.removeClass('pattern2 pattern3');
			if( $container.hasClass("normal") ){
				var patterns = ['','pattern2','pattern3'];
				var pattern = patterns[Math.floor(Math.random()*3)]
				if(pattern){
					$container.addClass(pattern);
				}
				$stream.find("li").each(function(i,v,a){
					var $li = $(this), src_g;
					var $grid_img = $li.find(".figure.grid");

					if($grid_img.height()>400){
						$grid_img.css("background-image", "url("+$grid_img.attr("data-ori-url")+")");
					}else{
						$grid_img.css("background-image", "url("+$grid_img.attr("data-310-url")+")");
					}
				});
			}

			var $sandbox = $('<div>'),
		    $contentBox = $('#content ol.stream'),
			$next, $rows;

			$sandbox[0].innerHTML = result.replace(/^[\s\S]+<body.+?>|<((?:no)?script|header|nav)[\s\S]+?<\/\1>|<\/body>[\s\S]+$/ig, '');
			$next = $sandbox.find('a.btn-more');
			$rows = $sandbox.find('#content ol.stream > li');

			$contentBox.append($rows);
			if(window.Modernizr && Modernizr.csstransitions )	$rows.css('opacity',0);

			$stream.trigger('itemloaded');

			if (tab!="suggestions" && $next.length) {
				url = $next.attr('href');
				$url.attr({
					'href' : $next.attr('href'),
					'ts'   : $next.attr('ts')
				});
				$stream.attr("ts",$currentTab.data("ts")||init_ts);
				$(window).trigger("prefetch.infiniteshow");
			} else {
				url = ''
				$url.attr({
					'href' : '',
					'ts'   : ''
				});
			}

			slideshow_request_url = '/home_slideshow.json?new-timeline&feed='+tab;
			Fancy.slideshow.reset();

			$wrapper.removeClass("wait");
			$wrapper.removeClass("swapping");
		}

		var done = function(){
			setTimeout(function(){$('#content ol.stream > li').css('opacity',1)},500);
		}

		$stream.trigger("changeloc");
		$wrapper.off('before-fadein').on('before-fadein', swapContent);
		$wrapper.off('after-fadein').on('after-fadein', done);
		$wrapper.trigger("redraw");
		$.cookie.set('timeline-feed',tab,9999);
	}

	$stream.on('changeloc',function(){
		$stream.attr("loc", ($feedtabs.filter(".current").attr("data-feed")||"featured") );
	})

	//when would "vertical" ever equal string "classic" wtf
	if("vertical"=="classic"){
		$wrapper.trigger("arrange");
	}

	//this event is registered in site/main4.js around line 1608
	//calls prefetch() in site/main4.js line 1444
	//not sure if it ever picks it up properly
	$(window).trigger("prefetch.infiniteshow");

	$stream.delegate('.figure-item',"mouseover",function(){
		if ($(this).parents('.timeline').hasClass('classic')==true) {
			$(this).find('.figure.classic .back')
				.width($(this).find('.figure.classic img').width())
				.height($(this).find('.figure.classic img').height())
				.css('margin-left',-($(this).find('.figure.classic img').width()/2)+'px')
				.css('margin-top',-($(this).find('.figure.classic img').height()/2)+'px')
				.css('left','50%')
				.css('top','50%')
			.end();
			$(this).find('.price').css('margin-top',($(this).find('.figure.classic').height()-$(this).find('.figure.classic img').height())/2+'px').css('margin-left',($(this).find('.figure.classic').width()-$(this).find('.figure.classic img').width())/2+'px');
			$(this).find('.share').css('margin-top',($(this).find('.figure.classic').height()-$(this).find('.figure.classic img').height())/2+'px').css('margin-right',($(this).find('.figure.classic').width()-$(this).find('.figure.classic img').width())/2+'px');
		}else{
			$(this).find('.figure.classic .back').removeAttr('style').end()
			.find('.price').removeAttr('style').end()
			.find('.figure.classic .share').removeAttr('style');
		}
	});
})();
</script>

<script>
/*        $.infiniteshow({
            itemSelector:'#content ol.stream > li',
            streamSelector:'#content ol.stream',
            dataKey:'home-new',
            post_callback: function($items){ $('ol.stream').trigger('itemloaded') },
            prefetch:true,

            newtimeline:true
        })
        if($.browser.msie) $.infiniteshow.option('prepare',1000);*/
    </script>
