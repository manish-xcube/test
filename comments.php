<?php 
//include "header.php";
include "left-menu.php";
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

$date_range=date('d.m.Y',$from).' - '.date('d.m.Y',$to);
//metohd user for color codes & survey details
$form_result = $db->getForm($_SESSION['session_user_id'],$_SESSION['form_id']);

if(!in_array(COMMENT_SCREEN,$page_access)) { echo '<div class="alert alert-block alert-danger page-err">'.PAGE_ACCESS_ERROR.'</div>'; exit; }

$result_counts=0;

$sorting_type_arr = array('mdate_asc'=>'date (oldest first)','mdate_desc'=>'date (newest first)','rating_desc'=>'rating (highest first)','rating_asc'=>'rating (lowest first)','locname_asc'=>'location','name_asc'=>'Submitter (a-> z)');

$sorting_category = array('mdate_asc'=>'date','mdate_desc'=>'date','rating_desc'=>'rating','rating_asc'=>'rating','locname_asc'=>'location','name_asc'=>'submitter');


	if(isset($_GET['job_id']) && $_GET['job_id']>0) {
	
		$jobData = $survey->savedJobsData($_REQUEST['job_id']);
		
		if($jobData['jobID'] > 0) {
		
		extract($jobData);

		$_SESSION['cm_saved_value']=1;
		$_SESSION['cm_comments']=explode(',',$comments);
		$_SESSION['cm_locations']=explode(',',$locations);	
		$_SESSION['cm_groupID']=$groups;	
		$_SESSION['cm_subgroupID']=$subGroups;	
		$_SESSION['cm_date_range']=$period;	
		}
}



	if(isset($_REQUEST['date_range'])) { 
	extract($_REQUEST);
	//	var_dump($_REQUEST);
	
	$date_range_arr = explode('-',$date_range);
	$from = strtotime(str_replace('.','-',$date_range_arr[0]));
	$to = strtotime(str_replace('.','-',$date_range_arr[1]));
	$result_counts = $survey->courseOfSurveyTable($from,$to,0,$groupID,$subgroupID);

	$_SESSION['cm_saved_value']=1;
	$_SESSION['cm_comments']=$comments;	
	$_SESSION['cm_locations']=$locations;	
	$_SESSION['cm_groupID']=$groupID;	
	$_SESSION['cm_subgroupID']=$subgroupID;	
	$_SESSION['cm_date_range']=$date_range;	
	}
	elseif(isset($_SESSION['cm_saved_value']) && $_SESSION['cm_saved_value']==1) {
	$comments=$_SESSION['cm_comments'];
	$locations = $_SESSION['cm_locations'];	
	$groupID = $_SESSION['cm_groupID'];	
	$subgroupID = $_SESSION['cm_subgroupID'];	
	$date_range = $_SESSION['cm_date_range'];	
	
	$date_range_arr = explode('-',$date_range);
	$from = strtotime(str_replace('.','-',$date_range_arr[0]));
	$to = strtotime(str_replace('.','-',$date_range_arr[1]));

	
	$surveyPeriodsList = $survey->surveyPeriodsArray($from,$to,"/");
	$result_counts = $survey->courseOfSurveyTable($from,$to,'',$groupID,$subgroupID);



	} else {
		
		$result_counts = $survey->courseOfSurveyTable($from,$to,0,'','');
	//	$commentsList = $survey->commentsList('itemShowAtItemSingle');
	
	}

?>
<script type="text/javascript">
function results_error()
{
document.getElementById('result_alert').style.display = 'block';
}

	

</script>

			<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
							<ul class="breadcrumb">
								<li>
									<i class="icon-home home-icon"></i>
									<i><?php echo $survey->getSurveyName($_SESSION['form_id']); ?></i></li>
								<li><i>Comments</i></li>
						   </ul><!-- .breadcrumb -->
				</div>
							
		<!-- PAGE CONTENT BEGINS -->
			<div class="page-content">
							<div class="col-xs-12">
							<div class="widget-box <?php echo $collapsed; ?>">
													<div class="widget-header">
													<h4>
														<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" data-action="collapse" href="#">
																<i data-icon-show="icon-angle-down" data-icon-hide="icon-angle-down" class="bigger-110 icon-angle-down"></i>														                                                   </a><span class="tab">MODIFY ANALYSIS</span>
													</h4>
												</div>
											<!--widget-body-->
											<div class="widget-body">
											  <!--widget-main-->
												<div class="widget-main">
											   <!--form row start form here-->
												<div class="row">
													<!--Col Start here-->
													   <div class="col-xs-12">
													   <form class="form-horizontal" id="myform" method="post" action="comments.php">
													      <div class="form-group">
																<label class="col-sm-3 control-label no-padding-right txt-color" for="form-field-1">COMMENTS :</label>
																   <div class="col-sm-9">
					<select name="comments[]" data-placeholder="All Comments......" id="comments" class="chosen-select" width="50" multiple="" style="display: none;">
			<?php echo $survey->commentsOptions($comments); ?>
			  </select>
														</div> 
													</div>
													
													<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right txt-color" for="form-field-1">LOCATIONS :</label>
														   <div class="col-sm-9">
																<select name="locations[]" data-placeholder="Choose a Location..." id="locations" class="chosen-select" width="50" multiple="" style="display: none;">
	<?php echo $survey->surveyLocationsOptions($locations); ?>

	</select>
															</div> 
															</div>
															
															<div class="form-group">
															<label class="col-sm-3 control-label no-padding-right txt-color" for="form-field-1">OPTIONAL SPECIFY GROUP :</label>
														   <div class="col-sm-9">
															  <select name="groupID" class="form-control" id="groupID">
																<option value="" selected="selected">All Participants</option>
																<?php echo $survey->surveyGroupOptions($groupID); ?>
																</select>
															</div> 
															</div>
															
															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right txt-color" for="form-field-3">SELECT SUB-GROUP :</label>
														   <div class="col-sm-9">
																<select class="form-control" id="subgroupID" name="subgroupID">															
																<?php if($subgroupID > 0) { echo $survey->subGroupOptions($groupID,$subgroupID); } ?>
																</select>	
															</div> 
															</div>
															
															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right txt-color" for="form-field-1">SELECT PERIOD :</label>
														   <div class="col-sm-9">
																<div class="input-group">
																	<span class="input-group-addon">
																		<i class="icon-calendar bigger-110"></i>																						                                                                     </span>
													<input type="text" id="id-date-range-picker-1" name="date_range" class="form-control" value="<?php echo $date_range; ?>" >
															  </div>
															</div>
															<div class="space-10"></div> 
															</div>
															
															<div class="form-group">
															<label class="col-sm-3 control-label no-padding-right txt-color" for="form-field-1"></label>
														   <div class="col-sm-9">
														     <button class="btn btn-info" type="submit" name="modify" value="submit">
																<i class="icon-ok bigger-110"></i>
																Submit</button>
														   </div>
														   </div>
													
														   
															<input type="hidden"  name="collapsed" />
															<input type="hidden"  id="sort1"   name="sort1" value="<?php echo $sort1; ?>" />
															<input type="hidden"  id="sort2" name="sort2" value="<?php echo $sort2; ?>" />
															
													</form>
													   </div>
												<!--Col Start here-->
												</div>	
										<!--form row end form here-->   
										</div> <!--widget-main end-->	
										</div>
										 <!--widget-body end-->
									</div><!--widget-box-collapsed end-->
													

					<div id="result_alert">
					<!--widget-box-collapsed end-->
					<!--Printable area Start-->
							<div class="tabbable tabs-left">
								<ul class="nav nav-tabs">
									<li class="active">
										<a href="#print" data-toggle="tab">
											PRINT COMMENTS</a></li>
									<li>
										<a href="#export_table" data-toggle="tab">
											EXPORT COMMENTS</a></li>
								</ul>
						<div class="tab-content">
									<div class="tab-pane active" id="print">
										 <div class="col-sm-9">
										 <p class="switch_text"><input class="ace ace-switch ace-switch-2 lbl" type="checkbox" name="switch-field-1" id="switch-field-1" checked="checked"><span class="lbl"></span>                                             Print Comment of the Submitter</p>
										<p class="switch_text"><input class="ace ace-switch ace-switch-2 lbl" type="checkbox" name="switch-field-2" id="switch-field-2" checked="checked"><span class="lbl"></span>                                             Print Headline of the Comment</p>
										<p class="switch_text"><input class="ace ace-switch ace-switch-2 lbl" type="checkbox" name="switch-field-3" id="switch-field-3" checked="checked"><span class="lbl"></span>                                             Print Annotations of All Account Users</p>
										</div>
										<div class="col-sm-3">
										  <button class="btn btn-app btn-light btn-xs" onclick="printButton1();"><br/><i class="icon-print bigger-160"></i>Print<br/><br/>
											 </button>
										</div>
									   </div>
								    <div class="tab-pane" id="export_table">
										 <div class="col-sm-9">
										 <p class="switch_text"><input class="ace ace-switch ace-switch-2 lbl" type="checkbox" name="switch-field-4" id="switch-field-4" checked="checked"><span class="lbl"></span>                                             Export Comment of the Submitter</p>
										<p class="switch_text"><input class="ace ace-switch ace-switch-2 lbl" type="checkbox" name="switch-field-5" id="switch-field-5" checked="checked"><span class="lbl"></span>                                             Export Headline of the Comment</p>
										<p class="switch_text"><input class="ace ace-switch ace-switch-2 lbl" type="checkbox" name="switch-field-6" id="switch-field-6" checked="checked"><span class="lbl"></span>                                             Export Annotations of All Account Users</p>
										</div>
										<div class="col-sm-3">
										  <button class="btn btn-app btn-light btn-xs" onclick="exportTable1();">
																  <br/>
																<i class="icon-save bigger-110"></i>
																save as <br/>
																doc <br/></button>
										</div>
									   </div>
										</div>
									<div class="space-24"></div>
									</div>
					<!--Print area End-->

					<!--Boxes with Scale-->
					  <div class="row">
						<?php include "scale.php"; ?>
						<div class="col-xs-12 col-sm-6 widget-container-span ui-sortable">
												<div class="widget-box">
													<div class="widget-header header-color-pink">
														<h4>Queries</h4>
		
														<div class="widget-toolbar">
															<a data-action="collapse" href="#">
																<i class="1 icon-chevron-up bigger-125"></i>
															</a>
														</div>
		
														<div class="widget-toolbar no-border" id="revert_field">
															<button class="btn btn-xs btn-light bigger-110" id="saved_jobs">
																Saved Jobs
															</button>
		
															<button class="btn btn-xs bigger-110 btn-yellow dropdown-toggle" id="save_job_btn" >
																Save Job
															</button>
														</div>
													</div>
		
													<div class="widget-body">
														<div class="widget-main slim-scroll" id="cm_queries_body"  data-height="250">
																
															</div>
														</div>
													</div>
												</div>
						<!--By default hide box-->
						
					   </div>
					 <!--Boxes with Scale-->					
					<div class="space-10"></div>
					<!--div sort by start-->	
						
							<div class="row">
							    <div class="col-xs-12">
							     <table style="max-width:100%">
								        <tr>
										<td>
										<div class="btn-group">
												<div class="btn btn-lg btn-warning no-hover unselectable" style="font-size:14px !important">Sort by</div>
												 <button class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
													<span class="icon-caret-down icon-only smaller-90"></span>												</button>

												<ul class="dropdown-menu dropdown-warning">
												
									<?php if($sort2=='') { ?>
												<?php foreach($sorting_type_arr as $key=>$value) { ?>
														<li><a href="javascript: void(0)" <?php if($sort1!=$key) { ?>class="sort1" rel="<?php echo $key; ?>" <?php } else { ?> style="color:#CCCCCC;"  <?php } ?> ><?php echo $value; ?></a></li>
												<?php } ?>
									
									<?php } else { ?>
													
													 <?php foreach($sorting_type_arr as $key=>$value) { ?>
														<li><a href="javascript: void(0)" <?php if($sorting_category[$sort2]!=$sorting_category[$key] && $sort1!=$key) { ?>class="sort1" rel="<?php echo $key; ?>" <?php } else { ?> style="color:#CCCCCC;"  <?php } ?> ><?php echo $value; ?></a></li>
													<?php } ?>
											<?php } ?>

												</ul>
											</div><!-- /btn-group -->										</td>
										<td>
										<div class="btn-group">
												<div class="btn btn-lg btn-warning no-hover unselectable" style="font-size:14px !important">Then sort by</div>
												<button class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
													<span class="icon-caret-down icon-only smaller-90"></span>												</button>
												<ul class="dropdown-menu dropdown-warning dropdown-preview">
																										
													 <?php 
													 foreach($sorting_type_arr as $key=>$value) { ?>
														<li><a href="javascript: void(0)" <?php if($sorting_category[$sort1]!=$sorting_category[$key] && $sort2!=$key) { ?>class="sort2" rel="<?php echo $key; ?>" <?php } else { ?> style="color:#CCCCCC;"  <?php } ?> ><?php echo $value; ?></a></li>
													<?php } ?>

												</ul>
											</div><!-- /btn-group -->										</td>
										</tr>
								</table>
								<div class="space-24"></div>
								</div>
							</div>
					</div>							
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
																	<span class="blue"><?php echo $fnc->commentSubmitterFormat($comment_row['name'] ,$comment_row['mail']); ?></span>																	
															   </h5>
															   <?php } ?>
															  
															</div>
															<div class="widget-body">
																<div class="widget-main">
							                                     <i><?php echo $comment_row['comment']; ?></i>
																	<div class="space-6"></div>
																	<div class="clearfix">
																	<?php $annonation_result = $survey->commentLogsForMainID($comment_row['id'],$comment_row['commentName']); 
	 																	  $aano_counts = mysql_num_rows($annonation_result);
																	?>
																		<div class="pull-left" id="addAnt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin;; ?>" <?php if($aano_counts!=0) { echo 'style="display:none"'; } ?>>
																			<i class="icon-hand-right grey bigger-125"></i>
																			<a href="javascript:void(0)" class="bigger-110" onClick="editor('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>');"  >Click to write an annotation to this comment.</a>																		</div>
																																			
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

													?>	
													<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>" style="margin-left:<?php echo $anno_margin; ?>px;">
															<div class="widget-box transparent dd-handle" id="bx-border">
															<div class="widget-header widget-header-small">
																<h5 class="smaller grey"><i>Annotations of <a href="mailto:<?php echo $logUserDetail['mail']; ?>" target=""><?php echo $logUserDetail['mail']; ?></a> on <span id="time<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>"><span class="blue"><?php echo date('d.m.Y',$commentLogDetail['commentLogTimestamp']); ?></span> at <span class="blue"><?php echo date('h:i',$commentLogDetail['commentLogTimestamp']); ?> h</span></span></i></h5>

														<?php if($annonation_result_row['commentLogUser']==$_SESSION['session_user_id']) { ?>
														<span class="widget-toolbar">
																			<a  href="javascript:void(0)"  onclick="editLog('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>');"><i class="icon-pencil blue bigger-110"></i></a>
																			<a href="javascript:void(0)"  onclick="removeLog('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>');"><i class="icon-remove red bigger-110"></i></a>												               </span>
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
																			<i class="icon-hand-right grey bigger-125"></i>
																			<a href="javascript:void(0)" class="bigger-110" onClick="editor('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>');"  >Click to write an annotation to this comment.</a>																			</div>
																			<?php } ?>
																	</div>
																</div>
															</div>
															</div>
													</div>
													<?php 
													
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
					  <?php } ?>	

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
															<div class="widget-body">
																<div class="widget-main">
							                                     <i><?php echo $comment_row['comment']; ?></i>
																	<div class="space-6"></div>
																	<div class="clearfix">
																	<?php $annonation_result = $survey->commentLogsForMainID($comment_row['id'],$comment_row['commentName']); 
	 																	  $aano_counts = mysql_num_rows($annonation_result);
																	?>
																		<div class="pull-left" id="addAnt<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin;; ?>" <?php if($aano_counts!=0) { echo 'style="display:none"'; } ?>>
																			<i class="icon-hand-right grey bigger-125"></i>
																			<a href="javascript:void(0)" class="bigger-110" onClick="editor('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>');"  >Click to write an annotation to this comment.</a>																		</div>
																																			
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

													?>	
													<div id="<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>" style="margin-left:<?php echo $anno_margin; ?>px;">
															<div class="widget-box transparent dd-handle" id="bx-border">
															<div class="widget-header widget-header-small">
																<h5 class="smaller grey"><i>Annotations of <a href="mailto:<?php echo $logUserDetail['mail']; ?>" target=""><?php echo $logUserDetail['mail']; ?></a> on <span id="time<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>"><span class="blue"><?php echo date('d.m.Y',$commentLogDetail['commentLogTimestamp']); ?></span> at <span class="blue"><?php echo date('h:i',$commentLogDetail['commentLogTimestamp']); ?> h</span></span></i></h5>

														<?php if($annonation_result_row['commentLogUser']==$_SESSION['session_user_id']) { ?>
														<span class="widget-toolbar">
																			<a  href="javascript:void(0)"  onclick="editLog('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>');"><i class="icon-pencil blue bigger-110"></i></a>
																			<a href="javascript:void(0)"  onclick="removeLog('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-'.$annonation_result_row['commentLogID']; ?>');"><i class="icon-remove red bigger-110"></i></a>												               </span>
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
																			<i class="icon-hand-right grey bigger-125"></i>
																			<a href="javascript:void(0)" class="bigger-110" onClick="editor('<?php echo $comment_row['commentName'].'-'.$comment_row['id'].'-0-'.$anno_margin; ?>');"  >Click to write an annotation to this comment.</a>																			</div>
																			<?php } ?>
																	</div>
																</div>
															</div>
															</div>
													</div>
													<?php 
													
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
						<?php } 
						
						if(mysql_num_rows($results1)+mysql_num_rows($results2) == 0 ) {  
							
							$error = 'There is no result found for ther period '.date('d.m.Y',$from).' to '.date('d.m.Y',$to).'<br>';
						 }// end of if condition ?>	
				
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


	
		<?php if(!empty($error)) {?>
		//alert('dfdsf');
		$('#result_alert').html('<div class="alert alert-block alert-danger"><?php echo $error; ?></div');
		<?php } ?>
	
	
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
	var comments = $("#comments").val();
	var locations = $("#locations").val();
	var groupID = $("#groupID").val();
	var subgroupID = $("#subgroupID").val();
	var date_range = $('input:text[name=date_range]').val();
	var sort1 = $("#sort1").val();
	var sort2 = $("#sort2").val();


	
	if($("#switch-field-1").is(':checked'))
    var switch1 = 'on'; // checked;
	else
    var switch1 = 'off'; 
	
	if($("#switch-field-2").is(':checked'))
	{
    var switch2 = 'on'; // checked;
	} else {
    var switch2 = 'off'; 
	}

	if($("#switch-field-3").is(':checked'))
    var switch3 = 'on'; // checked;
	else
    var switch3 = 'off'; 




//	alert(switch2);
		   $.post( 
		 "comments-print.php",
		 { comments: comments, locations: locations, groupID: groupID, subgroupID: subgroupID, date_range: date_range, sort1: sort1, sort2: sort2, switch1: switch1, switch2: switch2, switch3: switch3},
		 function(data) {
		
			  var w = window.open('about:blank', 'print','height=' + (screen.height - 100) + ',width=' + (screen.width - 50) + ',resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=yes');
				w.document.write(data);
				w.document.close();
			    w.print();
		 }
	
	  );

}
	
	function exportTable1() {
	

	var comments = $("#comments").val();
	var locations = $("#locations").val();
	var groupID = $("#groupID").val();
	var subgroupID = $("#subgroupID").val();
	var date_range = $('input:text[name=date_range]').val();
	var sort1 = $("#sort1").val();
	var sort2 = $("#sort2").val();

	if($("#switch-field-4").is(':checked'))
    var switch4 = 'on'; // checked;
	else
    var switch4 = 'off'; 
	
	if($("#switch-field-5").is(':checked'))
	{
    var switch5 = 'on'; // checked;
	} else {
    var switch5 = 'off'; 
	}

	if($("#switch-field-6").is(':checked'))
    var switch6 = 'on'; // checked;
	else
    var switch6 = 'off'; 

	
	

					$('<form>', {
					"id": "exportTableData",
					"method": "post",
					"html":$('#myform').html() +'<input type="text" name="switch4" value="'+switch4+'" ><input type="text" name="switch5" value="'+switch5+'" ><input type="text" name="switch6" value="'+switch6+'" >',
					"action": 'comments-doc.php'
					}).appendTo(document.body).submit().remove();

	}
	</script>
					
	<script>
			$('.period_choice').on('click', function(e){
				//alert($(this).attr('rel'));
				var choice = $(this).attr('rel');
				$("#result_type").val(choice);
				$("#myform").submit();
			});
	
			$('#save_job_btn').on('click', function(ev){
			var action = 'sessionData';
			 $.post( 
				 "ajax/commentSavedJobsAjax.php",
				 { action: action},
				 function(data) {
					$('#cm_queries_body').html(data);
				 }
			  );
			});	
			
			function save_form(jobTitle){
			var action = 'sessionData';
			 $.post( 
				 "ajax/commentSavedJobsAjax.php",
				 { action: action, jobTitle : jobTitle},
				 function(data) {
					$('#cm_queries_body').html(data);
				 }
			  );
			}	

			
			
			$('#saved_jobs').on('click', function(ev){
				jobsList();
			});				
		
			function jobsList(){
				var action = 'jobs';
				 $.post( 
					 "ajax/commentSavedJobsAjax.php",
					 { action: action},
					 function(data) {
						$('#cm_queries_body').html(data);
					 }
				  );
			}


		
			function saveJob(){
				var action = 'save';
				var jobTitle = $('#jobTitle').val();
				 $.post( 
					 "ajax/commentSavedJobsAjax.php",
					 { action: action, jobTitle: jobTitle},
					 function(data) {
						$('#cm_queries_body').html(data);
					 }
				  );
			}
			

			function delete_job(jobID) {
			
				var result = confirm("Are you sure to delete this job?");
				if (result==true) {
						var action = 'delete';
						//alert(jobID);
						 $.post( 
							 "ajax/commentSavedJobsAjax.php",
							 { action: action,jobID: jobID},
							 function(data) {
								jobsList();
							 }
						  );
				} 						  
			}


			function edit_form(jobID) {
				
				var action = 'edit_form';
				//alert(action);
				 $.post( 
					 "ajax/commentSavedJobsAjax.php",
					 { action: action,jobID: jobID},
					 function(data) {
						$('#cm_queries_body').html(data);
					 }
				  );
			}


			function edit_save(jobID) {
				var action = 'edit_save';
				var jobTitle = $('#jobTitle').val();
				//alert(jobID);
				 $.post( 
					 "ajax/commentSavedJobsAjax.php",
					 { action: action, jobTitle: jobTitle, jobID: jobID},
					 function(data) {
						$('#cm_queries_body').html(data);
					 }
				  );
				
			}



			$(document).ready(function(){
			jobsList();
			});  



		</script>

					
	
	</div><!--main container-->
	</body>
	</html>
