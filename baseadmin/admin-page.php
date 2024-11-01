<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div id="tn-admin" class="tn-admin-page">
    
    <div class="tn-header">
		<div class="brand"><?php tn_admin_brand_line(); ?></div>
		<h1><?php tn_admin_title(); ?></h1>
        <?php tn_admin_notice(); ?>
        <div class="version"><?php tn_admin_version_line(); ?></div>
		<?php tn_admin_quick_links(); ?>
		<div class="clear"></div>
    </div>

    <form method="post"<?php if(isset($_COOKIE['tnMenuCollapse']) && $_COOKIE['tnMenuCollapse']) echo ' class="tn-collapse-menu"'; ?>>
		<?php $tabs = tn_admin_get_tabs(); $count = count($tabs); if($count > 1): ?>
		<ul class="tn-nav-menu">
			<?php $i = 0;foreach($tabs as $tab): $i++;	?>
			<li>
				<i class="<?php echo esc_attr($tab->icon); ?>"></i>
				<span><?php echo $tab->title; ?></span>
			</li>
			<?php endforeach; ?>
			<li class="collapse hide-if-no-js">
				<i class="fa"></i>
				<span><?php esc_html_e('Collapse menu', 'themenow-framework'); ?></span>
			</li>
		</ul>
		<?php endif; ?>
    
		<ul class="<?php echo $count==1 ? 'tn-opt-group' : 'tn-opt-groups'; ?>">
			<?php $i=0; foreach($tabs as $tab): $i++;?>
			<li class="group group-<?php echo esc_attr(strtolower(str_replace(' ', '-', $tab->title))); ?>">
				<?php if($tab->desc): ?>
					<div class="group-desc"><?php echo $tab->desc; ?></div>
				<?php endif;?>
				<?php echo $tab->options; ?>
			</li>
			<?php endforeach; ?>
		</ul>
		
		<div class="clear"></div>
		<p class="submit">
			<input class="button" name="save" type="submit" value="<?php esc_html_e('Save changes', 'themenow-framework'); ?>" />    
			<input type="hidden" name="action" value="save" />
			<?php wp_nonce_field( 'save', '_tnnonce' );?>
		</p>
    </form>
</div>
