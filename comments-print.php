<?php 
// it is print all comments
include "header.php";
//include "left-menu.php";
$anno_margin=30;
$chart1_categories ="";
$chart1_series1_sum = "";
$chart1_series1 = "";
$locations=$survey->locationsLists();
$comments = array(1);
$groupID="";
$subgroupID="";
$collapsed="collapsed";
$sort1="mdate_desc";
$sort2="";
$to = strtotime(date('Y-m-d'));
$defaultDays = $survey->defaultDaysForPeriod();
$from =   strtotime("-$defaultDays days");  		
$error="";

$date_range=date('d/m/Y',$from).' - '.date('d/m/Y',$to);
//metohd user for color codes & survey details
$form_result = $db->getForm($_SESSION['session_user_id'],$_SESSION['form_id']);

if(!empty($form_result['pageaccess'])) {
$page_access = explode(',',$form_result['pageaccess']);
}

if(!in_array(COMMENT_SCREEN,$page_access)) { echo '<div class="alert alert-block alert-danger page-err">'.PAGE_ACCESS_ERROR.'</div>'; exit; }


$result_counts=0;

$sorting_type_arr = array('mdate_asc'=>'date (oldest first)','mdate_desc'=>'date (newest first)','rating_desc'=>'rating (highest first)','rating_asc'=>'rating (lowest first)','locname_asc'=>'location','name_asc'=>'Submitter (a-> z)');



if(isset($_REQUEST['date_range'])) { 
	extract($_REQUEST);
//	var_dump($_REQUEST);
	
	$date_range_arr = explode('-',$date_range);
	$from = strtotime(str_replace('/','-',$date_range_arr[0]));
	$to = strtotime(str_replace('/','-',$date_range_arr[1]));
	$result_counts = $survey->courseOfSurveyTable($from,$to,0,$groupID,$subgroupID);
	
	
} 
?>
<script type="text/javascript">

document.getElementById('navbar').style.display = 'none';

function results_error()
{
document.getElementById('result_alert').style.display = 'block';
}

</script>

<style type="text/css">
.main-content {
margin-left:100px;
width:70%;
}
</style>


			<div class="main-content" >
					
							
		<!-- PAGE CONTENT BEGINS -->
			<div class="page-content">
							<div class="col-xs-12">
							<!--widget-box-collapsed end-->
													

					
					<!--widget-box-collapsed end-->
					<!--Printable area Start-->
							
					<!--Print area End-->
						<!--div sort by start-->	
						
							
							<!--main row end-->	
					<!--comment row start from here-->
					<div id="all_comments">
					<?php 				
					$surveyConn =  $survey->surveyDbConn();	
					$result_query1 = $survey->commentsQuery($comments,$from,$to,$locations,$groupID,$subgroupID,$sort1,$sort2);
					$results1 = mysql_query($result_query1,$surveyConn);
					
					$result_query2 = $survey->commentsQueryNoRating($comments,$from,$to,$locations,$groupID,$subgroupID,$sort1,$sort2);
					$results2 = mysql_query($result_query2,$surveyConn);

						
						if(mysql_num_rows($results1)) { 
							while($comment_row = mysql_fetch_assoc($results1)) {
							$anno_margin=30;
							?>		
							<div class="row">
								<div class="col-xs-12">
								     <div id="timeline-1">
											<div class="timeline-container">
												<div class="timeline-label">
													<span class="label label-primary arrowed-in-right label-lg">
														<b><?php echo date('d.m.Y',$comment_row['currenttime']); ?>	</b>													</span>												</div>
												<div class="timeline-info">
														   <ul class="wizard-steps">
															   <li data-target="#step2">
																<span class="step btn-warning no-hover"><?php echo $comment_row['rating']!=77 ? $comment_row['rating'] : '-/-'; ?></span>
																	<span class="label label-info label-sm"><?php echo date('h:i',$comment_row['currenttime']); ?></span>																</li>
														   </ul>
														</div>
													<div class="widget-box transparent dd-handle" id="bx-border">
															<?php if($switch2=='on') { ?>
															<div class="widget-header widget-header-small">
															<span class="widget-toolbar no-border">
																	<i class="icon-time bigger-110"></i>
																	<?php echo date('h:i',$comment_row['currenttime']); ?>																</span>

																<span class="widget-toolbar">
																	<a href="#" data-action="collapse">
																		<i class="icon-chevron-up bigger-110"></i>																	</a>																</span>
																<h5 class="smaller tab2">
																<i class="icon-sitemap grey"></i>
																	<span class="grey tab1">LOCATION: </span>
																	<span class="blue"><?php echo $comment_row['locname']; ?></span>																							
																</h5>
																<h5 class="smaller tab2">
																<i class="icon-list-ul grey"></i>
																	<span class="grey tab1">ITEM: </span>
																	<span class="blue"><?php echo $survey->itemNameToItemText($comment_row['itemName']); ?></span>																</h5>
																<?php if($form_result['ulevel']==1) { ?>
																<h5 class="smaller tab2">
																<i class="icon-user grey"></i>
																	<span class="grey tab1">NAME OF THE SUBMITTER:</span>
																	<span class="blue"><?php echo $comment_row['name'] ?></span>																	
															   </h5>
															   <?php } ?>
															   
															</div>
															<?php } ?>
															<div class="widget-body">
																<div class="widget-main">
							                                     <i><?php if($switch1=='on') {  echo $comment_row['comment']; } ?></i>
																	<div class="space-6"></div>
																	<div class="clearfix">
																	<?php $annonation_result = $survey->commentLogsForMainID($comment_row['id'],$comment_row['commentName']); 
	 																	  $aano_counts = mysql_num_rows($annonation_result);
																	?>
																		<div class="pull-left" id="addAnt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin;; ?>" <?php if($aano_counts!=0) { echo 'style="display:none"'; } ?>>
																			
																																				</div>
																																			
																	</div>
																</div>
																<div id=""></div>																									
														</div>
														</div>
												<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id']; ?>">
													<?php
													$log_counts = mysql_num_rows($annonation_result);
													$anno_margin=30;
													while($annonation_result_row = mysql_fetch_assoc($annonation_result)) {
													//print_r($annonation_result_row) ;
													$log_counts--; 
													$logUserDetail = $db->getUser($annonation_result_row['commentLogUser']);
													$commentLogDetail=$survey->commentLogDetail($annonation_result_row['commentLogID']);
													
													//if($annonation_result_row['commentLogUser']!=$_SESSION['session_user_id']) {
													
														if($switch3=='on') { $showLog = 'yes'; } else { $showLog = 'no'; }
													
												//	} else { 
												//	$showLog = 'yes';
												//	}
												
													if($showLog=='yes') {	// check other user annonation												
													?>	
													<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>" style="margin-left:<?php echo $anno_margin; ?>px;">
															<div class="widget-box transparent dd-handle" id="bx-border">
															<div class="widget-header widget-header-small">
																<h5 class="smaller grey"><i>Annotations of <?php echo $logUserDetail['mail']; ?> on <span id="time<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>"><span class="blue"><?php echo date('d.m.Y',$commentLogDetail['commentLogTimestamp']); ?></span> at <span class="blue"><?php echo date('h:i',$commentLogDetail['commentLogTimestamp']); ?> h</span></span></i></h5>

														<?php if($annonation_result_row['commentLogUser']==$_SESSION['session_user_id']) { ?>
														<span class="widget-toolbar">
																			<i class="icon-pencil blue bigger-110"></i>
																			<i class="icon-remove red bigger-110"></i>												               </span>
														<?php } ?>																			
																<span class="widget-toolbar">
																	<a href="#" data-action="collapse">
																		<i class="icon-chevron-up bigger-110"></i>																	</a>																</span>															</div>
															<div class="widget-body widget_body1">
																<div class="widget-main">
																	<i><div id="txt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>"><?php echo $commentLogDetail['commentLogText']; ?></div></i>
																	<div class="space-6"></div>
																	<div class="clearfix">
																		<?php 
																		$anno_margin=$anno_margin+30;
																		if($log_counts==0) { 
																		?>
																		<div class="pull-left" id="addAnt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>">
																			
																		</div>
																			<?php } ?>
																	</div>
																</div>
															</div>
															</div>
													</div>
													<?php 
													} // end of if condition
													
													} ?>	
													<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>" style="margin-left:<?php echo $anno_margin; ?>px;" ></div>													
												</div>	
												



											</div>
											<div class="space-20"></div>
										<!-- /.timeline-container -->
				<!--comment row end from here-->
			
				   </div><!--page content end-->
   		         </div>
					<!-- main content end here-->
				</div>

	 						<?php } // inner comment loop for data ?>		
						<?php } // end of if condition ?>	

						<?php
						if(mysql_num_rows($results2)) { 
							while($comment_row = mysql_fetch_assoc($results2)) {
							$anno_margin=30;
							?>		
							<div class="row">
								<div class="col-xs-12">
								     <div id="timeline-1">
											<div class="timeline-container">
												<div class="timeline-label">
													<span class="label label-primary arrowed-in-right label-lg">
														<b><?php echo date('d.m.Y',$comment_row['currenttime']); ?>	</b>													</span>												</div>
												<div class="timeline-info">
														   <ul class="wizard-steps">
															   <li data-target="#step2">
																<span class="step btn-warning no-hover"><?php echo $comment_row['rating']!=77 ? $comment_row['rating'] : '-/-'; ?></span>
																	<span class="label label-info label-sm"><?php echo date('h:i',$comment_row['currenttime']); ?></span>																</li>
														   </ul>
														</div>
													<div class="widget-box transparent dd-handle" id="bx-border">
															<?php if($switch2=='on') { ?>
															<div class="widget-header widget-header-small">
															<span class="widget-toolbar no-border">
																	<i class="icon-time bigger-110"></i>
																	<?php echo date('h:i',$comment_row['currenttime']); ?>																</span>

																<span class="widget-toolbar">
																	<a href="#" data-action="collapse">
																		<i class="icon-chevron-up bigger-110"></i>																	</a>																</span>
																<h5 class="smaller tab2">
																<i class="icon-sitemap grey"></i>
																	<span class="grey tab1">LOCATION: </span>
																	<span class="blue"><?php echo $comment_row['locname']; ?></span>																							
																</h5>
																<h5 class="smaller tab2">
																<i class="icon-list-ul grey"></i>
																	<span class="grey tab1">ITEM: </span>
																	<span class="blue"><?php echo $survey->itemNameToItemText($comment_row['itemName']); ?></span>																</h5>
																<?php if($form_result['ulevel']==1) { ?>
																<h5 class="smaller tab2">
																<i class="icon-user grey"></i>
																	<span class="grey tab1">NAME OF THE SUBMITTER:</span>
																	<span class="blue"><?php echo $comment_row['name'] ?></span>																	
															   </h5>
															   <?php } ?>
															   
															</div>
															<?php } ?>
															<div class="widget-body">
																<div class="widget-main">
							                                     <i><?php if($switch1=='on') {  echo $comment_row['comment']; } ?></i>
																	<div class="space-6"></div>
																	<div class="clearfix">
																	<?php $annonation_result = $survey->commentLogsForMainID($comment_row['id'],$comment_row['commentName']); 
	 																	  $aano_counts = mysql_num_rows($annonation_result);
																	?>
																		<div class="pull-left" id="addAnt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin;; ?>" <?php if($aano_counts!=0) { echo 'style="display:none"'; } ?>>
																			
																																				</div>
																																			
																	</div>
																</div>
																<div id=""></div>																									
														</div>
														</div>
												<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id']; ?>">
													<?php
													$log_counts = mysql_num_rows($annonation_result);
													$anno_margin=30;
													while($annonation_result_row = mysql_fetch_assoc($annonation_result)) {
													//print_r($annonation_result_row) ;
													$log_counts--; 
													$logUserDetail = $db->getUser($annonation_result_row['commentLogUser']);
													$commentLogDetail=$survey->commentLogDetail($annonation_result_row['commentLogID']);
													
													//if($annonation_result_row['commentLogUser']!=$_SESSION['session_user_id']) {
													
														if($switch3=='on') { $showLog = 'yes'; } else { $showLog = 'no'; }
													
												//	} else { 
												//	$showLog = 'yes';
												//	}
												
													if($showLog=='yes') {	// check other user annonation												
													?>	
													<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>" style="margin-left:<?php echo $anno_margin; ?>px;">
															<div class="widget-box transparent dd-handle" id="bx-border">
															<div class="widget-header widget-header-small">
																<h5 class="smaller grey"><i>Annotations of <?php echo $logUserDetail['mail']; ?> on <span id="time<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>"><span class="blue"><?php echo date('d.m.Y',$commentLogDetail['commentLogTimestamp']); ?></span> at <span class="blue"><?php echo date('h:i',$commentLogDetail['commentLogTimestamp']); ?> h</span></span></i></h5>

														<?php if($annonation_result_row['commentLogUser']==$_SESSION['session_user_id']) { ?>
														<span class="widget-toolbar">
																			<i class="icon-pencil blue bigger-110"></i>
																			<i class="icon-remove red bigger-110"></i>												               </span>
														<?php } ?>																			
																<span class="widget-toolbar">
																	<a href="#" data-action="collapse">
																		<i class="icon-chevron-up bigger-110"></i>																	</a>																</span>															</div>
															<div class="widget-body widget_body1">
																<div class="widget-main">
																	<i><div id="txt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>"><?php echo $commentLogDetail['commentLogText']; ?></div></i>
																	<div class="space-6"></div>
																	<div class="clearfix">
																		<?php 
																		$anno_margin=$anno_margin+30;
																		if($log_counts==0) { 
																		?>
																		<div class="pull-left" id="addAnt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>">
																			
																		</div>
																			<?php } ?>
																	</div>
																</div>
															</div>
															</div>
													</div>
													<?php 
													} // end of if condition
													
													} ?>	
													<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>" style="margin-left:<?php echo $anno_margin; ?>px;" ></div>													
												</div>	
												



											</div>
											<div class="space-20"></div>
										<!-- /.timeline-container -->
				<!--comment row end from here-->
			
				   </div><!--page content end-->
   		         </div>
					<!-- main content end here-->
				</div>

	 						<?php } // inner comment loop for data ?>		
						<?php } // end of if condition ?>	

				<!-- PAGE CONTENT Ends-->
				</div>
			<!-- basic scripts -->
		<!--[if !IE]> -->

	<?php include "survey-footer.php"; ?>
	
			<!--Editor js-->
		<script src="assets/js/jquery.hotkeys.min.js"></script>
		<script src="assets/js/bootstrap-wysiwyg.min.js"></script>
		<!--Editor js-->


<script type="text/javascript">

	function publish_data(id) {
	var annoMainDiv = id.split('-');
	annoMainDiv = annoMainDiv[0]+'-'+annoMainDiv[1];
	//alert($('#editor-'+id).text());
		if($('#editor-'+id).text()!='') 
		{
			var annoText=$('#editor-'+id).html()
			
			var x = new Date(); 
			var h = x.getHours(); 
			var m = x.getMinutes(); 
			var s = x.getSeconds();
				
			var cr_time = h+':'+m+':'+s;
		
		
			   $.post( 
					 "ajax/addAnnoAjax.php",
					 { annonation: annoText, id: id, current_time: cr_time },
					 function(data) {
					   // $('#'+id).html(data);
					   $('#'+id).remove();
					   $('#addAnt'+id).hide();
					   $('#'+annoMainDiv).append(data);
					 
					 }
		
				  );
		 } else {
		 alert('It cannot be blank.')
		 }
	}
	
	function cancel_data(id) {
//	alert($('#editor-'+id).text());
	$('#'+id).html('');
	$('#addAnt'+id).show();
	}

// to remove the comment log
	function removeLog(id) {
//	alert('#addAnt'+id);
	
	var annoMainDiv = id.split('-');
	annoMainDiv = annoMainDiv[0]+'-'+annoMainDiv[1];
    var addLink = annoMainDiv+'-'+0+'-'+30;
	var addDiv = '<div id="'+addLink+'" style="margin-left:30px;" ></div>'
	
	 $.post( 
             "ajax/deleteAnnoAjax.php",
             { id: id },
             function(data) {
			 	if(data!=0){
                $('#'+annoMainDiv).html(data);
				} else {
					$('#'+annoMainDiv).html(addDiv);
					$('#addAnt'+addLink).show();
				}
		     //  $('#'+id).remove();
             }

          );
	}
	
	function reset_data(id) {
//	alert($('#editor-'+id).text());
	var html_data = $('#edit_html_'+id).html();
	$('#'+id).html(html_data);
	}
	

	function update_data(id) {
	//alert($('#editor-'+id).text());
	
		if($('#editor-'+id).text()!='') 
		{
				var annoText=$('#editor-'+id).html();
				
				var html_data = $('#edit_html_'+id).html();
				
				
					var x = new Date(); 
					var h = x.getHours(); 
					var m = x.getMinutes(); 
					var s = x.getSeconds();
					
					var cr_time = h+':'+m+':'+s;
			
				   $.post( 
						 "ajax/updateAnnoAjax.php",
						 { annonation: annoText, id: id , current_time: cr_time },
						 function(data) {
							$('#'+id).html(html_data)
							$('#txt'+id).html(annoText);
							$('#time'+id).html(data);
					 
						 }
			
					  );
		 } else {
		  alert('It cannot be blank.')
		 }				  

	}


		
	function editor(id){
	
	var ed_id = "'"+id+"'";
	
	var stuff = '<div class="widget-box" id="add_new_comment'+id+'"  >';
	stuff +=	'<div id="widget-header'+id+'" class="widget-header widget-header-small  header-color-green">';
	stuff +=	'<div class="widget-toolbar">';
	stuff +=	'<a href="#" data-action="collapse">';
	stuff +=	'<i class="icon-chevron-up"></i></a></div></div>';

	stuff +=	'<div class="widget-body">';
	stuff +=	'<div class="widget-main no-padding">';
	stuff +=	'<div class="wysiwyg-editor" id="editor-'+id+'"></div>';
	stuff +=	'</div>';
	stuff +=	'<div class="widget-toolbox padding-4 clearfix">';
	stuff +=	'<div class="btn-group pull-left">';
	stuff +=	'<button class="btn btn-sm btn-grey" onClick="cancel_data('+ed_id+');">';
	stuff +=	'<i class="icon-remove bigger-125"></i>Cancel</button>';
	stuff +=	'</div><div class="btn-group pull-right">';
	stuff +=	'<button class="btn btn-sm btn-success"  onClick="publish_data('+ed_id+');">';
	stuff +=	'<i class="icon-globe bigger-125"></i>Publish';
	stuff +=	'<i class="icon-arrow-right icon-on-right bigger-125"></i></button>';
	stuff +=	'</div>';
	stuff +=	'</div>';
	stuff +=	'</div>';
	stuff +=	'</div>';
	
	
//	alert('#'+id);
	$('#'+id).html(stuff);
	

							jQuery(function($){
					$('#editor-'+id).css({'height':'200px'}).ace_wysiwyg({
						toolbar_place: function(toolbar) {
							return $(this).closest('#add_new_comment'+id).find('#widget-header'+id).empty().prepend(toolbar).children(0).addClass('inline');
						},
						toolbar:
						[
							'bold',
							{name:'italic' , title:'Change Title!', icon: 'icon-leaf'},
							'strikethrough',
							null,
							'insertunorderedlist',
							'insertorderedlist',
							null,
							'justifyleft',
							'justifycenter',
							'justifyright'
						],
						speech_button:false
					});
					//Add Image Resize Functionality to Chrome and Safari
					//webkit browsers don't have image resize functionality when content is editable
					//so let's add something using jQuery UI resizable
					//another option would be opening a dialog for user to enter dimensions.
					if ( typeof jQuery.ui !== 'undefined' && /applewebkit/.test(navigator.userAgent.toLowerCase()) ) {
						
						var lastResizableImg = null;
						function destroyResizable() {
							if(lastResizableImg == null) return;
							lastResizableImg.resizable( "destroy" );
							lastResizableImg.removeData('resizable');
							lastResizableImg = null;
						}
				
						var enableImageResize = function() {
							$('.wysiwyg-editor')
							.on('mousedown', function(e) {
								var target = $(e.target);
								if( e.target instanceof HTMLImageElement ) {
									if( !target.data('resizable') ) {
										target.resizable({
											aspectRatio: e.target.width / e.target.height,
										});
										target.data('resizable', true);
										
										if( lastResizableImg != null ) {//disable previous resizable image
											lastResizableImg.resizable( "destroy" );
											lastResizableImg.removeData('resizable');
										}
										lastResizableImg = target;
									}
								}
							})
							.on('click', function(e) {
								if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
									destroyResizable();

								}
							})
							.on('keydown', function() {
								destroyResizable();
							});
						}
						
						enableImageResize();
				
						/**
						//or we can load the jQuery UI dynamically only if needed
						if (typeof jQuery.ui !== 'undefined') enableImageResize();
						else {//load jQuery UI if not loaded
							$.getScript($path_assets+"/js/jquery-ui-1.10.3.custom.min.js", function(data, textStatus, jqxhr) {
								if('ontouchend' in document) {//also load touch-punch for touch devices
									$.getScript($path_assets+"/js/jquery.ui.touch-punch.min.js", function(data, textStatus, jqxhr) {
										enableImageResize();
									});
								} else	enableImageResize();
							});
						}
						*/
				}
				});
			
	$('#addAnt'+id).hide();


 				}



	function editLog(id){
	
	var logData =  $('#txt'+id).html();

	var htmlDivData =  $('#'+id).html();
	//alert(logData);
	

	
	var ed_id = "'"+id+"'";
	
	var stuff = '<div class="widget-box" id="add_new_comment'+id+'"  >';
	stuff +=	'<div id="widget-header'+id+'" class="widget-header widget-header-small  header-color-green">';
	stuff +=	'<div class="widget-toolbar">';
	stuff +=	'<a href="#" data-action="collapse">';
	stuff +=	'<i class="icon-chevron-up"></i></a></div></div>';

	stuff +=	'<div class="widget-body">';
	stuff +=	'<div class="widget-main no-padding">';
	stuff +=	'<div class="wysiwyg-editor" id="editor-'+id+'">'+logData+'</div>';
	stuff +=	'</div>';
	stuff +=	'<div class="widget-toolbox padding-4 clearfix">';
	stuff +=	'<div class="btn-group pull-left">';
	stuff +=	'<button class="btn btn-sm btn-grey" onClick="reset_data('+ed_id+');">';
	stuff +=	'<i class="icon-remove bigger-125" ></i>Cancel</button>';
	stuff +=	'</div><div class="btn-group pull-right">';
	stuff +=	'<button class="btn btn-sm btn-success"  onClick="update_data('+ed_id+');">';
	stuff +=	'<i class="icon-globe bigger-125"></i>Update';
	stuff +=	'<i class="icon-arrow-right icon-on-right bigger-125"></i></button>';
	stuff +=	'</div>';
	stuff +=	'</div>';
	stuff +=	'</div>';
	stuff +=	'</div>';
	
	
//	alert('#'+id);
	$('#'+id).html(stuff);
	$('#'+id).append('<div id="edit_html_'+id+'" style="display:none;">'+htmlDivData+'</div>');
	

							jQuery(function($){
					$('#editor-'+id).css({'height':'200px'}).ace_wysiwyg({
						toolbar_place: function(toolbar) {
							return $(this).closest('#add_new_comment'+id).find('#widget-header'+id).empty().prepend(toolbar).children(0).addClass('inline');
						},
						toolbar:
						[
							'bold',
							{name:'italic' , title:'Change Title!', icon: 'icon-leaf'},
							'strikethrough',
							null,
							'insertunorderedlist',
							'insertorderedlist',
							null,
							'justifyleft',
							'justifycenter',
							'justifyright'
						],
						speech_button:false
					});
					//Add Image Resize Functionality to Chrome and Safari
					//webkit browsers don't have image resize functionality when content is editable
					//so let's add something using jQuery UI resizable
					//another option would be opening a dialog for user to enter dimensions.
					if ( typeof jQuery.ui !== 'undefined' && /applewebkit/.test(navigator.userAgent.toLowerCase()) ) {
						
						var lastResizableImg = null;
						function destroyResizable() {
							if(lastResizableImg == null) return;
							lastResizableImg.resizable( "destroy" );
							lastResizableImg.removeData('resizable');
							lastResizableImg = null;
						}
				
						var enableImageResize = function() {
							$('.wysiwyg-editor')
							.on('mousedown', function(e) {
								var target = $(e.target);
								if( e.target instanceof HTMLImageElement ) {
									if( !target.data('resizable') ) {
										target.resizable({
											aspectRatio: e.target.width / e.target.height,
										});
										target.data('resizable', true);
										
										if( lastResizableImg != null ) {//disable previous resizable image
											lastResizableImg.resizable( "destroy" );
											lastResizableImg.removeData('resizable');
										}
										lastResizableImg = target;
									}
								}
							})
							.on('click', function(e) {
								if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
									destroyResizable();

								}
							})
							.on('keydown', function() {
								destroyResizable();
							});
						}
						
						enableImageResize();
				
						/**
						//or we can load the jQuery UI dynamically only if needed
						if (typeof jQuery.ui !== 'undefined') enableImageResize();
						else {//load jQuery UI if not loaded
							$.getScript($path_assets+"/js/jquery-ui-1.10.3.custom.min.js", function(data, textStatus, jqxhr) {
								if('ontouchend' in document) {//also load touch-punch for touch devices
									$.getScript($path_assets+"/js/jquery.ui.touch-punch.min.js", function(data, textStatus, jqxhr) {
										enableImageResize();
									});
								} else	enableImageResize();
							});
						}

						*/
				}
				});
			
	$('#addAnt'+id).hide();


 				}

</script>
			
<script>
	

	/////////////////////////////////////////////////////////
			//                Time Line                           //
			///////////////////////////////////////////////////////
			
			
			$('[data-toggle="buttons"] .btn').on('click', function(e){
					var target = $(this).find('input[type=radio]');
					var which = parseInt(target.val());
					$('[id*="timeline-"]').addClass('hide');
					$('#timeline-'+which).removeClass('hide');
				});
			
			
			
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			}) 
	
			
			
	</script>					
	
	<script>
	$('.sort1').on('click', function(e){
	//			alert($(this).attr('rel'));
				var sort1_value = $(this).attr('rel');
	//			alert(sort1_value);
				$("#sort1").val(sort1_value);
				$("#myform").submit();
	});	
	
	$('.sort2').on('click', function(e){
	//			alert($(this).attr('rel'));
				var sort2_value = $(this).attr('rel');
	//			alert(sort2_value);
				$("#sort2").val(sort2_value);
				$("#myform").submit();
	});
		

	function printButton1() {
	var params = [
		'height='+screen.height,
		'width='+screen.width,
	].join(',');
	var mywindow = window.open('', '','height=' + (screen.height - 100) + ',width=' + (screen.width - 50) + ',resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=yes');
	mywindow.document.write('<!DOCTYPE html><html lang="en"><head><title>Print</title>');
	mywindow.document.write($('head').html());
	mywindow.document.write('</head><body><div class="page-content"><div class="col-xs-12"><div id="all_comments">');
	
	//if(($("#tableCheckBox").is(":checked"))){ mywindow.document.write($("#dataTable").html())};
	//if(($("#barChartCheckBox").is(":checked"))){ mywindow.document.write($("#barChartPrintable").html())};
	
	mywindow.document.write();

	mywindow.document.write('</div></div></div></body></html>');
	console.log(mywindow);
	//setTimeout(function(){
		mywindow.print();
	//	mywindow.close();
	//},1000);
//	mywindow.close();
	return true;
}
				

	document.window.print();
	
	</script>
					


					
	
	</div><!--main container-->
	</body>
	</html>
