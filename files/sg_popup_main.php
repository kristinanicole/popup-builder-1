<?php
	global $wpdb;

	$pagenum = isset($_GET['pn']) ? (int) $_GET['pn'] : 1;

	$limit = SG_APP_POPUP_TABLE_LIMIT;//;
	$offset = ($pagenum - 1) * $limit;

	$total = $wpdb->get_var( "SELECT COUNT(id) FROM ". $wpdb->prefix ."sg_promotional_popup" );
	$num_of_pages = ceil( esc_html($total) / $limit );
	if ($pagenum>$num_of_pages || $pagenum < 1) {
		$offset = 0;
		$pagenum = 1;
	}

	$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_promotional_popup ORDER BY id DESC LIMIT %d,%d", $offset, $limit);
	$entries = $wpdb->get_results($st);
?>
<div class="wrap">
	<div class="headersWrapper">
		<h1>Popups</h1>
		<div class="creteLinkWrapper">
		
			<a id='linkCreate' href='<?php echo admin_url();?>admin.php?page=create-popup'>Create new</a>
		</div>
		<?php 
			if(!SG_POPUP_PRO) { ?>
				<input type="button" class="mainUpdateToPro" value="Upgrade to PRO version" onclick="window.open('<?php echo SG_POPUP_PRO_URL;?>')">
			<?php }
		?>
	</div>
	<table class="widefat">
		<thead>
			<tr>
				<th scope="col" class="manage-column column-name" style="">ID</th>
				<th scope="col" class="manage-column column-name" style="">Title</th>
				<th scope="col" class="manage-column column-name" style="">Type</th>
				<th scope="col" class="manage-column column-name" style="">Options</th>
			</tr>
		</thead>	
		<tfoot>
			<tr>
				<th scope="col" class="manage-column column-name" style="">ID</th>
				<th scope="col" class="manage-column column-name" style="">Title</th>
				<th scope="col" class="manage-column column-name" style="">Type</th>
				<th scope="col" class="manage-column column-name" style="">Options</th>
			</tr>
		</tfoot>	
		<tbody>
			<?php if($entries) { ?>			
			<?php
				$count = 1;
				$class = '';
				foreach( $entries as $entry ) {
					$class = ( $count % 2 == 0 ) ? ' class="alternate"' : '';
					$jsonData = json_decode($entry->options, true);
					$title = $jsonData['title'];
				?>
				<tr <?php echo $class; ?>>
					<td><?php echo esc_html($entry->id); ?></td>
					<td><?php echo esc_html($title); ?></td>
					<td><?php echo esc_html($entry->content); ?></td>
					<td><a href='<?php echo admin_url();?>admin.php?page=create-popup&id=<?php echo esc_html($entry->id);?>'>Edit</a><a href="#" sg-app-popup-id = "<?php echo esc_html($entry->id);?>" class='sgDeleteLink'>Delete</a></td>
				</tr>
				<?php
					$count++;
				}
				?>
			<?php } else { ?>
			<tr>
				<td colspan="2">No popups</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php
	
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pn', '%#%' ),
		'format' => '',
		'prev_text' => __( '&laquo;', 'aag' ),
		'next_text' => __( '&raquo;', 'aag' ),
		'total' => $num_of_pages,
		'current' => $pagenum

	));
	if ( $page_links ) {
		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}
	?>
</div>