<?php
	$t_item = $this->getVar("item");
	$va_comments = $this->getVar("comments");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");	
	$va_access_values = caGetUserAccessValues($this->request);
	$vs_rep_viewer = trim($this->getVar("representationViewer"));
	if(!$vs_rep_viewer){
		$vs_rep_viewer = $t_item->getWithTemplate("<ifcount code='ca_objects' min='1' restrictToRelationshipTypes='feature'><unit relativeTo='ca_objects' restrictToRelationshipTypes='feature'><div class='repViewerCont'><l>^ca_object_representations.media.large</l><div class='small text-center'>^ca_objects.preferred_labels</div></div></unit></ifcount>", array("checkAccess" => $va_access_values));
	}		
				
?>
<div class="container">
<div class="row">
	<div class='col-xs-12 navTop'><!--- only shown at small screen size -->
		{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}
	</div><!-- end detailTop -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>
			<div class="row">
				<div class='col-md-12 col-lg-12 text-center'>
					<H1>{{{^ca_occurrences.type_id}}}</H1>
					<H2>{{{^ca_occurrences.preferred_labels.name}}}</H2>
					<hr/>
				</div><!-- end col -->
			</div><!-- end row -->
			<div class="row">			
<?php
			if($vs_rep_viewer){
?>
				<div class='col-sm-4 col-md-4 col-lg-4'>
					
					<?php print $vs_rep_viewer; ?>
				
				
				<div id="detailAnnotations"></div>
				
				<?php print caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_item, array("returnAs" => "bsCols", "linkTo" => "carousel", "bsColClasses" => "smallpadding col-sm-3 col-md-3 col-xs-4", "primaryOnly" => $this->getVar('representationViewerPrimaryOnly') ? 1 : 0)); ?>
				
					
				</div><!-- end col -->
				<div class='col-sm-8 col-md-8 col-lg-8'>
<?php
			}else{
?>
				<div class='col-sm-12'>
<?php
			}
?>
					{{{<ifdef code="ca_occurrences.descriptionWithSource.prodesc_text"><div class='unit trimText'><label>Description</label><unit relativeTo="ca_occurrences.descriptionWithSource.prodesc_text" delimiter="<br/><br/>">^ca_occurrences.descriptionWithSource.prodesc_text</unit></div></ifdef>}}}
				</div>
			</div><!-- end row -->
			<div class="row">
				<div class='col-sm-12'>
<?php					
					$va_productions = $t_item->get('ca_occurrences.related', array('restrictToTypes' => array('production'), 'returnWithStructure' => true, 'checkAccess' => $va_access_values));
					if (sizeof($va_productions)) {
						$va_related_list = array();
						foreach ($va_productions as $va_production) {
							$t_occ = new ca_occurrences($va_production['occurrence_id']);
							$vs_date = $t_occ->get("ca_occurrences.date", array("delimiter" => ", "));
							$va_related_list[] = caDetailLink($this->request, $va_production['name'].(($vs_date) ? "<br/>".$vs_date : ""), '', 'ca_occurrences', $va_production['occurrence_id']);
						}
						print "<div class='unit'><H3>Productions</H3><div class='unit detailLinksGrid'>";
						$i = 0;
						$c = 0;
						foreach ($va_related_list as $vs_link) {
							if($i == 0){
								print "<div class='row'>";
							}
							print "<div class='col-sm-12 col-md-4'><div class='detailLinksGridItem'>".$vs_link."</div></div>";
							$i++;
							$c++;
							if($i == 3){
								print "</div>";
								$i = 0;
							}
							if($c == 18){
								break;
							}
						}
						if($i > 0){
							print "</div>";
						}
						print "</div></div><!-- end unit -->";
						
					}
					$va_events = $t_item->get('ca_occurrences.related', array('restrictToTypes' => array('training','special_event'), 'returnWithStructure' => true, 'checkAccess' => $va_access_values));
					if (sizeof($va_events)) {
						$va_related_list = array();
						foreach ($va_events as $va_event) {
							$t_occ = new ca_occurrences($va_production['occurrence_id']);
							$vs_date = $t_occ->get("ca_occurrences.eventDate", array("delimiter" => ", "));
							if(!$vs_date){
								$vs_date = $t_occ->get("ca_occurrences.training_date", array("delimiter" => ", "));
							}
							$va_related_list[] = caDetailLink($this->request, $va_event['name'].(($vs_date) ? "<br/>".$vs_date : ""), '', 'ca_occurrences', $va_event['occurrence_id']);
						}
						print "<div class='unit'><H3>Trainings & Special Events</H3><div class='unit detailLinksGrid'>";
						$i = 0;
						$c = 0;
						foreach ($va_related_list as $vs_link) {
							if($i == 0){
								print "<div class='row'>";
							}
							print "<div class='col-sm-12 col-md-4'><div class='detailLinksGridItem'>".$vs_link."</div></div>";
							$i++;
							$c++;
							if($i == 3){
								print "</div>";
								$i = 0;
							}
							if($c == 18){
								break;
							}
						}
						if($i > 0){
							print "</div>";
						}
						print "</div></div><!-- end unit -->";
						
					}
					if((sizeof($va_productions) > 18) || (sizeof($va_events) > 18)){
						print "<div class='unit text-center'>".caNavLink($this->request, "View All Productions, Trainings & Special Events", "btn btn-default", "", "Browse", "productions", array("facet" => "venue_facet", "id" => $t_item->get("ca_occurrences.occurrence_id")))."</div>";
					}
?>					
					
					
				</div><!-- end col -->
			</div><!-- end row -->
{{{<ifcount code="ca_objects" min="1">
			<div class="unit"><H3>Media</H3>
				<div id="browseResultsContainer">
					<unit relativeTo="ca_objects" length="21" delimiter=" " aggregateUnique="1">
						<div class="bResultItemCol col-xs-12 col-sm-4">
							<div class="bResultItem" id="row^ca_objects.object_id">
								<div class="bResultItemContent"><div class="text-center bResultItemImg"><case><ifcount code="ca_object_representations.media.medium" min="1"><l>^ca_object_representations.media.medium</l></ifcount><ifcount code="ca_object_representations" min="0" max="0"><l><?php print "<div class='bResultItemImgPlaceholderLogo'><i class='fa fa-picture-o fa-2x' aria-label='media placeholder'></i></div>"; ?></l></ifcount></case></div>
									<div class="bResultItemText">
										<small>^ca_objects.type_id</small><br/>
										<l>^ca_objects.preferred_labels.name</l>
									</div><!-- end bResultItemText -->
								</div><!-- end bResultItemContent -->
							</div><!-- end bResultItem -->
						</div><!-- end col -->
					</unit>
				</div><!-- end browseResultsContainer -->
			</div><!-- end unit -->
			<ifcount code="ca_objects" min="21">
				<div class="unit text-center">
					<?php print caNavLink($this->request, "View All Media", "btn btn-default", "", "Browse", "media", array("facet" => "venue_facet", "id" => $t_item->get("ca_occurrences.occurrence_id"))); ?>
				</div>
			</ifcount>
</ifcount>}}}
	</div><!-- end col -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->
</div><!-- end container -->
<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 400
		});
	});
</script>