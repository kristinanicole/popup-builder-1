<div class="htmlType">
<?php
		$content = wp_kses_post($sgPopupDataHtml);
		$editor_id = 'sg_popup_html';
		$settings = array(
			'teeny' => true,
			'tinymce' => array(
				'width' => '100%',
			),
			'textarea_rows' => '6',

			 'editor_css' => '<style> #mceu_27-body{
				 width: 100%;
			 } </style>',
			'media_buttons' => true
		);
		wp_editor( $content, $editor_id, $settings ); 
	?>
</div>