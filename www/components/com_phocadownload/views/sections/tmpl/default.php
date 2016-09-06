<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 

if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
		<?php echo $this->params->get('page_title'); ?>
	</div>
<?php endif; ?>

<div id="phoca-dl-sections-box">
<?php
if (!empty($this->section)) {
	$i = 1;
	foreach ($this->section as $value) {
		// Categories
		$numDoc 	= 0;
		$catOutput 	= '';
		foreach ($value->categories as $valueCat) {
			$catOutput 	.= '<p class="pd-category">';
			$catOutput 	.= '<a href="'. JRoute::_('index.php?option=com_phocadownload&view=category&id='.$valueCat->id.':'.$valueCat->alias.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')).'">'. $valueCat->title.'</a>';
			
			if ($this->tmpl['displaynumdocsecs'] == 1) {
				$catOutput  .=' <small>('.$valueCat->numdoc .')</small>';
			}
			
			$catOutput 	.= '</p>' . "\n";
			$numDoc = (int)$valueCat->numdoc + (int)$numDoc;
		}
		
		echo '<div class="pd-sections"><div><div><div><h3>';
		echo '<a href="'. JRoute::_('index.php?option=com_phocadownload&view=section&id='.$value->id.':'.$value->alias.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')).'">'. $value->title.'</a>';
		
		if ($this->tmpl['displaynumdocsecsheader'] == 1) {
			echo ' <small>('.$value->numcat.'/' . $numDoc .')</small>';
		}
		echo '</h3>';
		echo $catOutput;	
		echo '</div></div></div></div>';
		if ($i%3==0) {
			echo '<div style="clear:both"></div>';
		}
		$i++;
	}
}
?>
</div>
<div style="clear:both"></div>


<?php
if (!empty($this->mostvieweddocs) && $this->tmpl['displaymostdownload'] == 1) {
	echo '<div class="phoca-dl-hr" style="clear:both">&nbsp;</div>';
	echo '<div id="phoca-dl-most-viewed-box">';
	echo '<div class="pd-documents"><h3>'. JText::_('Most downloaded files').'</h3>';
	foreach ($this->mostvieweddocs as $value) {
	
		// FILESIZE
		if ($value->filename !='') {
			$absFile = str_replace('/', DS, JPath::clean($this->absfilepath . $value->filename));
			if (JFile::exists($absFile))
			{
				$fileSize = PhocaDownloadHelper::getFileSizeReadable(filesize($absFile));
			} else {
				$fileSize = '';
			}
		}
		
		// IMAGE FILENAME
		$imageFileName = '';
		if ($value->image_filename !='') {
			$thumbnail = false;
			$thumbnail = preg_match("/phocathumbnail/i", $value->image_filename);
			if ($thumbnail) {
				$imageFileName 	= '';
			} else {
				$imageFileName = 'style="background: url(\''.$this->cssimagepath.$value->image_filename.'\') 0 center no-repeat;"';
			}
		}
	
		echo '<div class="pd-document'.$this->tmpl['file_icon_size_md'].'" '.$imageFileName.'>';
		echo '<a href="'. JRoute::_('index.php?option=com_phocadownload&view=category&id='.$value->categoryid.':'.$value->categoryalias.'&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int')).'">'. $value->title.'</a> <small>(' .$value->sectiontitle. '/'.$value->categorytitle.')</small>';
		
		echo PhocaDownloadHelper::displayNewIcon($value->date, $this->tmpl['displaynew']);
		echo PhocaDownloadHelper::displayHotIcon($value->hits, $this->tmpl['displayhot']);		

		echo '</div>' . "\n";
	}
	echo '</div></div>';
}
echo $this->tmpl['id'];
?>
