<?php
/**
* @package   yoo_phoenix Template
* @version   1.5.2 2009-07-02 16:34:50
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="joomla <?php echo $this->params->get('pageclass_sfx')?>">
	<div class="newsfeeds">
	
		<?php if ($this->params->get('show_page_title', 1)) : ?>
		<h1 class="pagetitle">
			<?php echo $this->escape($this->params->get('page_title')); ?>
		</h1>
		<?php endif; ?>

		<?php if ( @$this->image || @$this->category->description ) : ?>
		<div class="description">
			<?php
				if ( isset($this->image) ) :  echo $this->image; endif;
				echo $this->category->description;
			?>
		</div>
		<?php endif; ?>
		
		<?php echo $this->loadTemplate('items'); ?>

	</div>
</div>