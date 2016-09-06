<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
//Flow Player
$document = &JFactory::getDocument();
$document->addScript($this->tmpl['playerpath'].'flowplayer-3.1.1.min.js');
?>
<div style="text-align:center;margin: 10px auto">
<div style="margin: 0 auto;text-align:center; width:<?php echo $this->tmpl['playerwidth']; ?>px"><a href="<?php echo $this->tmpl['playfilewithpath']; ?>"  style="display:block;width:<?php echo $this->tmpl['playerwidth']; ?>px;height:<?php echo $this->tmpl['playerheight']; ?>px" id="player"></a><?php

if ($this->tmpl['filetype'] == 'mp3') {
	?><script>flowplayer("player", "<?php echo $this->tmpl['playerpath']; ?>flowplayer-3.1.1.swf",
	{ 
		plugins: { 
			controls: { 
				fullscreen: false, 
				height: <?php echo $this->tmpl['playerheight']; ?> 
			} 
		}
	}
	);</script><?php
} else {
	?><script>flowplayer("player", "<?php echo $this->tmpl['playerpath']; ?>flowplayer-3.1.1.swf");</script><?php
}
?></div></div>


