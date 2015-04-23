<?php

// THIS IS WHERE WE CLEANUP EVERYTHING!!
$cleaneddb = '';

// Post revisions
if(isset($_POST['cleanup-rev'])) {
	$cleaneddb .= Clean_Post_Revisions();	
}

// Spam comments
if(isset($_POST['cleanup-spam'])) {
	$cleaneddb .= Clean_Spam_Comments();
}

// Unapproved comments
if(isset($_POST['cleanup-unapproved'])) {
	$cleaneddb .= Clean_Unapproved_Comments();
}

// Unusewd tags
if(isset($_POST['cleanup-tags'])) {
	$cleaneddb .= Clean_Unused_Tags();
}

// Unused post meta
if(isset($_POST['cleanup-postmeta'])) {
	$cleaneddb .= Clean_Unused_Post_Meta();
}

// Optimize MySQL tables
if(isset($_POST['cleanup-mysql'])) {
	$cleaneddb .= Optimize_Tables();
}

// Function for cleaning up the post revisions
function Clean_Post_Revisions() {
	global $wpdb; 
	
	$query	= 'DELETE FROM ' . $wpdb->posts . ' WHERE post_type=\'revision\'';
	$result	= $wpdb->query($query);
	
	return 'Post revisions cleaned up!<br />';
}

// Function for cleaning up the spam comments
function Clean_Spam_Comments() {
	global $wpdb; 
	
	$query	= 'DELETE FROM ' . $wpdb->comments . ' WHERE comment_approved=\'spam\'';
	$result	= $wpdb->query($query);
	
	return 'Spam comments cleaned up!<br />';
}

// Function for cleaning up the unapproved comments
function Clean_Unapproved_Comments() {
	global $wpdb; 
	
	$query	= 'DELETE FROM ' . $wpdb->comments . ' WHERE comment_approved=\'0\'';
	$result	= $wpdb->query($query);
	
	return 'Unapproved comments cleaned up!<br />';
}

// Function for cleaning up the unused tags
function Clean_Unused_Tags() {
	global $wpdb;
	
	$query	= 'DELETE wt,wtt FROM ' . $wpdb->terms . ' wt INNER JOIN ' . $wpdb->term_taxonomy . ' wtt ON wt.term_id=wtt.term_id WHERE wtt.taxonomy=\'post_tag\' AND wtt.count=0';
	$result	= $wpdb->query($query);
	
	return 'Unused tags cleaned up!<br />';
}

// Function for cleaning up the unused postmeta
function Clean_Unused_Post_Meta() {
	global $wpdb;
	
	$query	= 'DELETE pm FROM ' . $wpdb->postmeta . ' pm LEFT JOIN ' . $wpdb->posts . ' wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL';
	$result	= $wpdb->query($query);
	
	return 'Unused post meta cleaned up!<br />';
}

// Function for optimizing MySQL tables
function Optimize_Tables() {
	global $wpdb;
	
	$query	= 'SHOW TABLE STATUS FROM ' . DB_NAME;
	$result = $wpdb->get_results($query, ARRAY_A);

	foreach($result as $row) {
		$optimize	= 'OPTIMIZE TABLE ' . $row['Name'];
		$execute	= $wpdb->query($optimize);
	}
	
	return 'MySQL tables optimized!<br />';
}

// Get the total size from the WordPress database
function Database_Size() {
	global $wpdb;
	
	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME;
	$query = $wpdb->get_results($sql, ARRAY_A);
	$totalusedspace = 0;
	
	foreach($query as $row) {
		$usedspace = $row['Data_length'] + $row['Index_length'];
		$usedspace = $usedspace / 1024;
		$usedspace = round($usedspace, 2);
		$totalusedspace += $usedspace;
	}
	
	return $totalusedspace;
}

// Get the total size of post revisions from the WordPress database
function Post_Revision_Size() {
	global $wpdb;
	
	$query = 'SELECT COUNT(`id`) FROM ' . $wpdb->posts . ' WHERE `post_type` = \'revision\'';
	$postrevision = $wpdb->get_var($query);
	
	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME . ' WHERE Name = \'' . $wpdb->posts . '\'';
	$query = $wpdb->get_results($sql, ARRAY_A);

	foreach($query as $result) {
		$size = ($result['Avg_row_length'] * $postrevision) / 1024;
		$size = round($size, 2);
	}
	
	return $size;
}

// Get the total post revisions from the WordPress database
function Post_Revisions_Total() {
	global $wpdb;
	
	$query = 'SELECT COUNT(`id`) FROM ' . $wpdb->posts . ' WHERE `post_type` = \'revision\'';
	$postrevision = $wpdb->get_var($query);
	
	return $postrevision;
}

// Get the total size of spam comments from the WordPress database
function Spam_Comment_Size() {
	global $wpdb;
	
	$query = 'SELECT COUNT(`comment_id`) FROM ' . $wpdb->comments . ' WHERE `comment_approved` = \'spam\'';
	$spam = $wpdb->get_var($query);
	
	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME . ' WHERE Name = \'' . $wpdb->comments . '\'';
	$query = $wpdb->get_results($sql, ARRAY_A);
	foreach($query as $result) {
		$size = ($result['Avg_row_length'] * $spam) / 1024;
		$size = round($size, 2);
	}
	
	return $size;
}

// Get the total spam comments from the WordPress database
function Spam_Comments_Total() {
	global $wpdb;
	
	$query = 'SELECT COUNT(`comment_id`) FROM ' . $wpdb->comments . ' WHERE `comment_approved` = \'spam\'';
	$spam = $wpdb->get_var($query);
	
	return $spam;
}

// Get the total size of unapproved comments from the WordPress database
function Unapproved_Comments_Size() {
	global $wpdb;
	
	$query = 'SELECT COUNT(`comment_id`) FROM ' . $wpdb->comments . ' WHERE `comment_approved` = \'0\'';
	$unapproved = $wpdb->get_var($query);
	
	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME . ' WHERE Name = \'' . $wpdb->comments . '\'';
	$query = $wpdb->get_results($sql, ARRAY_A);
	foreach($query as $result) {
		$size = ($result['Avg_row_length'] * $unapproved) / 1024;
		$size = round($size, 2);
	}
	
	return $size;
}

// Get the total unapproved comments from the WordPress database
function Unapproved_Comment_Total() {
	global $wpdb;
	
	$query = 'SELECT COUNT(`comment_id`) FROM ' . $wpdb->comments . ' WHERE `comment_approved` = \'0\'';
	$unapproved = $wpdb->get_var($query);
	
	return $unapproved;
}

// Get the total size of unused MySQL Data from the WordPress database
function Unused_MySQL_Size() {
	global $wpdb;
	
	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME;
	$query = $wpdb->get_results($sql, ARRAY_A);
	$totalunusedspace = 0;
	
	foreach($query as $row) {
		$unusedspace = $row['Data_free'] / 1024;
		$unusedspace = round($unusedspace, 2);
		$totalunusedspace   += $unusedspace;
	}
	
	return $totalunusedspace;
}

// Get the total size of unused MySQL Data from the WordPress database
function Unused_MySQL_Table_Size() {
	global $wpdb;
	
	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME;
	$query = $wpdb->get_results($sql, ARRAY_A);
	$mysql_table_size = array();
	
	foreach($query as $row) {
		$unusedspace = $row['Data_free'] / 1024;
		$unusedspace = round($unusedspace, 2);
		if ($unusedspace > 0) {
			$mysql_table_size[] = array(
				'Name' => $row['Name'],
				'Unused_Space' => $unusedspace,
				'Unused_Percent' => Division(Database_Size(), $unusedspace)
			);
		}
	}
	
	foreach($mysql_table_size as $row) {
?>
		<tr>
			<td></td>
			<td><?php echo json_decode('"' . '\u00BB' . '"') . ' ' . $row['Name']; ?></td>
			<td></td>
			<td><?php echo $row['Unused_Space']; ?> kb</td>
			<td><?php echo $row['Unused_Percent']; ?>%</td>
		</tr>
<?php
	}
}

// Get the total size of useful WordPress data from the WordPress database
function Useful_WordPress_Data_Size() {
	$useful = Database_Size() - Unused_MySQL_Size() - Post_Revision_Size() - Unapproved_Comments_Size() - Spam_Comment_Size();
	
	return $useful;
}

// Get the total size of unused post meta in the WordPress database
function Unused_Post_Meta_Size() {
	global $wpdb;
	
	$query = 'SELECT COUNT(pm.meta_id) FROM ' . $wpdb->postmeta . ' pm LEFT JOIN ' . $wpdb->posts . ' wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL';
	$postmeta = $wpdb->get_var($query);

	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME . ' WHERE Name = \'' . $wpdb->postmeta . '\'';
	$query = $wpdb->get_results($sql, ARRAY_A);
	foreach($query as $result) {
		$size = ($result['Avg_row_length'] * $postmeta) / 1024;
		$size = round($size, 2);
	}
	return $size;
}

// Get the total count of unused post meta in the WordPress database
function Unused_Post_Meta_Total() {
	global $wpdb;
	
	$query = 'SELECT COUNT(pm.meta_id) FROM ' . $wpdb->postmeta . ' pm LEFT JOIN ' . $wpdb->posts . ' wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL';
	$postmeta = $wpdb->get_var($query);

	$size = round($postmeta, 2);
	
	return $size;
}

// Get the total size of unused tags in the WordPress database
function Unused_Tags_Size() {
	global $wpdb;
	
	$query = 'SELECT COUNT(wt.term_id) FROM ' . $wpdb->terms . ' wt INNER JOIN ' . $wpdb->term_taxonomy . ' wtt ON wt.term_id=wtt.term_id WHERE wtt.taxonomy=\'post_tag\' AND wtt.count=0';
	$tags = $wpdb->get_var($query);

	$sql = 'SHOW TABLE STATUS FROM ' . DB_NAME . ' WHERE Name = \'' . $wpdb->terms . '\'';
	$query = $wpdb->get_results($sql, ARRAY_A);
	foreach($query as $result) {
		$size = ($result['Avg_row_length'] * $tags) / 1024;
		$size = round($size, 2);
	}
	return $size;
}

// Get the total number of unused tags in the WordPress database
function Unused_Tags_Total() {
	global $wpdb;
	
	$query = 'SELECT COUNT(wt.term_id) FROM ' . $wpdb->terms . ' wt INNER JOIN ' . $wpdb->term_taxonomy . ' wtt ON wt.term_id=wtt.term_id WHERE wtt.taxonomy=\'post_tag\' AND wtt.count=0';
	$tags = $wpdb->get_var($query);

	$size = round($tags, 2);
	
	return $size;
}


// Do division
function Division($total, $division) {
	$division = ($division / $total) * 100;
	$division = round($division, 2);
	
	return $division;
}
?>

<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('WP-CleanDB'); ?></h2>

	<?php if($cleaneddb <> '') { ?>
	<div id="message" class="updated fade"><p><?php echo $cleaneddb; ?></p></div>
	<?php } ?>

	<h3>At a glance</h3>
	<div align="center">
		<img src="http://chart.apis.google.com/chart?cht=p&chs=850x300&chco=247AA2&chf=bg,s,F9F9F9&chl=Useful+WordPress+Data|Post+Revisions|Spam+Comments|Unapproved+Comments|Unused+MySQL+Data|Unused+Tags|Unused+Post+Meta&chd=t:<?php echo Division(Database_Size(), Useful_WordPress_Data_Size()); ?>,<?php echo Division(Database_Size(), Post_Revision_Size()); ?>,<?php echo Division(Database_Size(), Spam_Comment_Size()); ?>,<?php echo Division(Database_Size(), Unapproved_Comments_Size()); ?>,<?php echo Division(Database_Size(), Unused_MySQL_Size()); ?>,<?php echo Division(Database_Size(), Unused_Tags_Size()); ?>,<?php echo Division(Database_Size(), Unused_Post_Meta_Size()); ?>">
	</div>
	
	<h3>Total report</h3>
	
	<form action="#" method="post" id="cleanup-form">
		<table class="widefat">
			<thead>
			 <th width="5%">Cleanup?</th>
			 <th width="50%">Description</th>
			 <th width="15%">Amount</th>
			 <th width="15%">Size</th>
			 <th width="15%">Percentage of Total</th>
			</thead>
			<tr>
				<td></td>
				<td>Database Size</td>
				<td></td>
				<td><?php echo Database_Size(); ?> kb</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td>Useful WordPress Data</td>
				<td></td>
				<td><?php echo Useful_WordPress_Data_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Useful_WordPress_Data_Size()); ?>%</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name="cleanup-rev" id="cleanup-rev"></td>
				<td>Post Revisions</td>
				<td><?php echo Post_Revisions_Total(); ?></td>
				<td><?php echo Post_Revision_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Post_Revision_Size()); ?>%</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name="cleanup-spam" id="cleanup-spam"></td>
				<td>Spam Comments</td>
				<td><?php echo Spam_Comments_Total(); ?></td>
				<td><?php echo Spam_Comment_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Spam_Comment_Size()); ?>%</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name="cleanup-unapproved" id="cleanup-unapproved"></td>
				<td>Unapproved Comments</td>
				<td><?php echo Unapproved_Comment_Total(); ?></td>
				<td><?php echo Unapproved_Comments_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Unapproved_Comments_Size()); ?>%</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name="cleanup-tags" id="cleanup-tags"></td>
				<td>Unused Tags</td>
				<td><?php echo Unused_Tags_Total(); ?></td>
				<td><?php echo Unused_Tags_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Unused_Tags_Size()); ?>%</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name="cleanup-postmeta" id="cleanup-postmeta" ></td>
				<td>Unused Post Meta</td>
				<td><?php echo Unused_Post_Meta_Total(); ?></td>
				<td><?php echo Unused_Post_Meta_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Unused_Post_Meta_Size()); ?>%</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name="cleanup-mysql" id="cleanup-mysql"></td>
				<td>Unused MySQL Data</td>
				<td></td>
				<td><?php echo Unused_MySQL_Size(); ?> kb</td>
				<td><?php echo Division(Database_Size(), Unused_MySQL_Size()); ?>%</td>
			</tr>
			
			<?php Unused_MySql_Table_Size(); ?>
			
		</table>
		
		<p>Make sure you have a backup of your WordPress database before cleanup!</p>
		<p>
			<input type="submit" name="submit" value="Cleanup the selected items!" style="width: 300px;" class="button-primary">
		</p>
	</form>
</div>