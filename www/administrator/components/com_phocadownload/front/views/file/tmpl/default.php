<?php
defined('_JEXEC') or die('Restricted access'); 
if ( $this->params->def( 'show_page_title', 1 ) ) {
	echo '<div class="componentheading'.$this->params->get( 'pageclass_sfx' ).'">'.$this->params->get('page_title').'</div>';
}
echo '<div id="phoca-dl-file-box">';
if (!empty($this->category[0])) {
	echo '<div class="pd-file">';
	if ($this->tmpl['display_up_icon'] == 1) {
		echo '<div class="pdtop"><a title="'.JText::_('Category').'" href="'. JRoute::_('index.php?option=com_phocadownload&view=category&id='.$this->category[0]->id.':'.$this->category[0]->alias. $this->tmpl['limitstarturl'] . '&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')).'" >'.JHTML::_('image', 'components/com_phocadownload/assets/images/up.png', JText::_('Up')).  '</a></div>';
	}
} else {
	echo '<div class="pd-file"><div class="pdtop"></div>';
}

if (!empty($this->file[0])) {	
	// Title or name		
	if ($this->tmpl['filename_or_name'] == 'title') {
		$displayName = $this->file[0]->title;
	} else if ($this->tmpl['filename_or_name'] == 'filename'){
		$displayName = PhocaDownloadHelper::getTitleFromFilenameWithExt( $this->file[0]->filename );
	} else if ($this->tmpl['filename_or_name'] == 'filenametitle'){
		$displayName = $this->file[0]->title . ' - '.PhocaDownloadHelper::getTitleFromFilenameWithExt( $this->file[0]->filename );
	}



	echo '<h3>'.$displayName. '</h3>';
	echo '<h4>'.JText::_('License Agreement').'</h4>';
	echo '<div id="phoca-dl-license" style="height:'.(int)$this->tmpl['licenseboxheight'].'px">'.$this->file[0]->licensetext.'</div>';
	echo '<form action="'.$this->request_url.'" method="post" name="phocaDownloadForm" id="phocadownloadform">';
	echo '<input type="checkbox" name="license_agree" onclick="enableDownload()" /> <span>'.JText::_('I agree to the terms listed above').'</span> ';
	echo '<input type="submit" name="submit" id="pdlicensesubmit" value="'.JText::_('Download').'" />';
	echo '<input type="hidden" name="download" value="'.$this->file[0]->id.'" />';
	echo '<input type="hidden" name="'. JUtility::getToken().'" value="1" />';
	echo '</form>';

	// For users who have disabled Javascript
	echo '<script type=\'text/javascript\'>document.forms[\'phocadownloadform\'].elements[\'pdlicensesubmit\'].disabled=true</script>';

} else {
	echo '<h3>&nbsp;</h3>';
}
echo '</div></div>'. $this->tmpl['id'];
?>

