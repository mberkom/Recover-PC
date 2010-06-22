<?php
	require("config.php");
	require("functions.php");
	
	// setup database
	db_connect($db);
		
	if(input_get("form_name")) {
		switch (input_get("form_name")) {
			case "add_device":
				$action = "new_device";
	
				$device_name = input_get("name");
				$device = insert_device($device_name);
				if(is_array($device)) {
					$notice = array(
						"class" => "success",
						"message" => "Your device has been successfully added. <a href='".BASEURL."/'>Back to homepage.</a>"
					);
				} else {
					$notice = array(
						"class" => "error",
						"message" => "ERROR! Device not added. <a href='".BASEURL."/'>Back to homepage.</a>"
					);
				}
				break;
			case "device_status":
				$action = "device_status";
				$hash = input_get("hash");
				
				if(input_get("status") != "") {
					mysql_query("
						UPDATE ".DEVICETABLE."
						SET is_stolen = ".input_get("status")."
						WHERE hash='{$hash}'
					");
				}
				
				$device = db_query_row("SELECT * FROM ".DEVICETABLE." WHERE hash = '{$hash}' ");
				$locations = db_query_rows("SELECT * FROM ".LOCATIONTABLE." WHERE device_id = {$device['id']} ORDER BY unixtime DESC");
								
				if(is_array($device)) {
					$notice = array(
						"class" => "success",
						"message" => "Device found. <a href='".BASEURL."/'>Back to homepage.</a>"
					);
				} else {
					$notice = array(
						"class" => "error",
						"message" => "Device not found. <a href='".BASEURL."/'>Back to homepage.</a>"
					);
				}
				
				break;
		}
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Recover PC</title>
		
		<link rel="stylesheet" href="blueprint.css" type="text/css" media="screen, projection"> 
	</head>
	<body>
		<div class="container">
			<h1>Recover PC</h1>
			
			<?php if(is_array($notice)): ?>
				<p class="<?=$notice['class']?>"><?=$notice['message']?></p>
			<?php endif; ?>
			<?php if($action == "new_device"): ?>
				<p>It's hash is: <?=$device['hash']?><p>
				<h3>Instructions:</h3>
				<p>Record this special hash string in a safe place accessible to you if your device is stolen.  Without it you will be unable to track or mark your device as stolen.  We suggest you email it to your self along with your device name (<?=$device['name']?>).</p>
			<?php elseif($action == "device_status"): ?>
				<h3>Device: <?=$device['name']?></h3>
				<form>
					<label for="status">Status: &nbsp;</label>
					<input type="hidden" name="form_name" value="device_status" />
					<input type="hidden" value="<?=$hash?>" name="hash"/>
					<select name="status" id="status">
						<option value="0" <?=($device['is_stolen'] == 0) ? "selected='selected'" : ""?>>Safe</option>
						<option value="1" <?=($device['is_stolen'] == 1) ? "selected='selected'" : ""?>>Stolen</option>
					</select>
					<button type="submit">Change</button>
				</form>
				<h4>Locations</h4>
				<table>
					<thead>
						<th>IP</th>
						<th>User Agent</th>
						<th>Time Recorded</th>
					</thead>
					<tbody>
					<?php if(is_array($locations)): ?>
						<?php foreach($locations as $l): ?>
							<tr>
								<td><?=$l['ip']?></td>
								<td><?=$l['user_agent']?></td>
								<td><?=date("g:i  a l, M Y", $l['unixtime'])?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			<?php else: ?>
				<hr> 
					<h2 class="alt">Protect your valuable devices using a simple but secure tracking system that will give you your device's location within minutes of it being marked as stolen.</h2> 
				<hr> 
				<div class="span-7 colborder"> 
				  <h6>Add Device</h6>
				  <form action="" method="get">
				  	<input type="hidden" name="form_name" value="add_device" />
				  	<label for="name">Name</label>
				  	<input type="text" value="" name="name" id="name"/>
				  	<button type="submit">Insert</button>
				  </form>
				  <p>Tracking a new device is easy, just add it to the database with one click!
				</div> 
				<div class="span-8 colborder"> 
				  <h6>Device Status</h6> 
				  <form action="" method="get">
				  	<input type="hidden" name="form_name" value="device_status" />
				  	<label for="hash">Your Hash</label>
				  	<input type="text" value="" name="hash" id="hash"/>
				  	<button type="submit">Get Status</button>
				  </form>
					<p>Keep your device information at your finger tips.  Access past and present locations as well as the current state of the device by entering its hash above.  
				</div> 
				<div class="span-7 last"> 
				  <h6>Donate</h6> 
				  <p>Although Recover PC is a non profit project, it does take money to keep the site up and for other minor expenses.  If you feel so inclined, drop us a tip with the donate button below.</p> 
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="URDL9PV9AXJW6">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div> 
			<?php endif; ?>
			<hr>
			<p>Recover PC Copyright to Michael Berkompas and licensed under the <a href="license.txt">MIT Licence.</a></p>
		</div>
	</body>
</html>