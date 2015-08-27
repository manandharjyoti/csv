<html>
<head>
	<title>Mapping</title>
</head>
<body>
	<?php
	$row = 1;
	echo "<pre>";
	$file = array_map('str_getcsv', file('file.csv'));
	$header = array_shift($file);
	$csv = array();
	foreach ($file as $row) {
		$csv[] = array_combine($header, $row);
	}
	?>
	<table border="1">
		<th>name</th>
		<?php foreach($csv as $data):?>
			<tr>

				<td><a href="#"><?php echo $data['contractname']?></a></td>
				

				<input type="hidden" class="latlon" value="<?php echo $data['latlon']?>"/>
				<td class="details">
			<h1>Details</h1>
					<p><?php echo "status".$data['status']?></p>

					<p><?php echo "bidPurchaseDeadline: ".$data['bidPurchaseDeadline']?></p>
					<p><?php echo "bidSubmissionDeadline: ".$data['bidSubmissionDeadline']?></p>
					<p><?php echo "bidOpeningDate: ".$data['bidOpeningDate']?></p>
					<p><?php echo "tenderid: ".$data['tenderid']?></p>
					<p><?php echo "publicationDate: ".$data['publicationDate']?></p>
					<p><?php echo "publishedIn: ".$data['publishedIn']?></p>
					<p><?php echo "contractDate: ".$data['contractDate']?></p>
					<p><?php echo "completionDate: ".$data['completionDate']?></p>
					<p><?php echo "awardee: ".$data['awardee']?></p>
					<p><?php echo "awardeeLocation: ".$data['awardeeLocation']?></p>
					<p><?php echo "Amount: ".$data['Amount']?></p>
					<div class="map" style="width: 300px; height: 300px;"></div>
				</td>

			</tr>

		<?php endforeach;?>
	</table>


</body>
</html>
<style type="text/css">
	.details{
	display: none;
}
</style>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("maps", "3.4", {
		other_params: "sensor=false&language=fr"
	});
</script>
<script type="text/javascript" src="jquery.googlemap.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.details').hide();
		$('a').click( function() { 
			$(".details").hide();
			$(this).parent().parent().find(".details").show();
			var latlon = $(this).parent().parent().find(".latlon").val() ; 

			if(latlon != "")
			{
				var location = latlon.split(",");
				var lat = location[0];
				var lon = location[1];
				$(this).parent().parent().find(".map").googleMap({
					zoom:6,
					coords:[lat , lon]
				});
				$(this).parent().parent().find(".map").addMarker({
					coords: [lat, lon],
					title: 'Location'
				});
			}
		});
		return false; 
	});


</script>
