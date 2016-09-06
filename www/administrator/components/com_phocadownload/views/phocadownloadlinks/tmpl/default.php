<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

echo '<div id="phocadownload-links">'
.'<fieldset class="adminform">'
.'<legend>'.JText::_( 'Select Link Type' ).'</legend>'
.'<ul>'
.'<li class="icon-16-edb-sections"><a href="'.$this->tmpl['linksections'].'">'.JText::_('Link to all sections').'</a></li>'
.'<li class="icon-16-edb-section"><a href="'.$this->tmpl['linksection'].'">'.JText::_('Link to section').'</a></li>'
.'<li class="icon-16-edb-category"><a href="'.$this->tmpl['linkcategory'].'">'.JText::_('Link to category').'</a></li>'
.'<li class="icon-16-edb-file"><a href="'.$this->tmpl['linkfile'].'">'.JText::_('Link to file').'</a></li>'
.'</ul>'
.'</div>'
.'</fieldset>'
.'</div>';
?>