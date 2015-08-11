<?php
	
	//Pagination::getLimit();
	$pagenum = isset($_GET['pn']) ? (int) $_GET['pn'] : 1;

	$limit = SG_APP_POPUP_TABLE_LIMIT;//;
	$offset = ($pagenum - 1) * $limit;
	$total = SGPopup::getTotalRowCount();
	$num_of_pages = ceil( esc_html($total) / $limit );
	if ($pagenum>$num_of_pages || $pagenum < 1) {
		$offset = 0;
		$pagenum = 1;
	}
	$orderBy = 'id DESC';
	$entries = SGPopup::findAll($orderBy,$limit,$offset);
?>
<div class="wrap">
	<div class="headersWrapper">
	<h2>Popups <a href="<?php echo admin_url();?>admin.php?page=create-popup" class="add-new-h2">Add New</a></h2>
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
				foreach( $entries as $entry ) { ?>
					<tr>
					<td><?php echo esc_html($entry->getId()); ?></td>
					<td><?php echo esc_html($entry->getTitle()); ?></td>
					<td><?php echo esc_html($entry->getType()); ?></td>
					<td><a href='<?php echo admin_url();?>admin.php?page=edit-popup&id=<?php echo esc_html($entry->getId());?>&type=<?php echo esc_html($entry->getType());?>'>Edit</a><a href="#" sg-app-popup-id = "<?php echo esc_html($entry->getId());?>" class='sgDeleteLink'>Delete</a></td>
				</tr>
				<?php }
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