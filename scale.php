					
<div class="col-xs-12 col-sm-6 widget-container-span ui-sortable">
										<div class="widget-box">
											<div class="widget-header header-color-pink">
												<h4>Scale</h4>

												<div class="widget-toolbar">
													<a data-action="collapse" href="#">
														<i class="1 icon-chevron-up bigger-125"></i>
													</a>
												</div>
											</div>
											<div class="widget-body">
												<div class="widget-main slim-scroll" data-height="250">
																									<div class="space-8">TEST</div>

												  <div class="table-responsive">
												    <table cellpadding="8" cellspacing="8">
													<?php 
													$scaleRatingColorsSq = $fnc->scaleRatingColors();
													while($scaleRow = mysql_fetch_assoc($scaleRatingColorsSq)) {
													?>
													<tr>
														 <td><span class="bubble0<?php echo $scaleRow['lessThan']; ?>"><?php echo $scaleRow['lessThan']; ?></span></td>
														 <td><spn class="Sbox_txt"><?php echo $scaleRow['legend']; ?></span></td>
													</tr>
													<?php } ?>													
													</table>
												  </div>
												</div>
											</div>
										</div>
									</div>
									
									