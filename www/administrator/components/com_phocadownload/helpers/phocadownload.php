<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaDownloadHelper
{	
	function filterCategory($query, $active = NULL) {
		$db	= & JFactory::getDBO();

		$categories[] = JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$category = JHTML::_('select.genericlist',  $categories, 'catid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $active);

		return $category;
	}
	
	function filterSection($query, $active = NULL) {
		$db	= & JFactory::getDBO();
	
		$sections[] = JHTML::_('select.option', '0', '- '.JText::_('Select Section').' -');
		$db->setQuery( $query );
		$sections = array_merge($sections, $db->loadObjectList());

		$section = JHTML::_( 'select.genericlist', $sections, 'filter_sectionid',  'class="inputbox" size="1" onchange="document.adminForm.submit( );"' , 'value', 'text', $active );
		
		return $section;
	}
	
	function resetHits($redirect, $id)
	{
		global $mainframe;

		// Initialize variables
		$db	= & JFactory::getDBO();

		// Instantiate and load an article table
		$row = & JTable::getInstance('content');
		$row->Load($id);
		$row->hits = 0;
		$row->store();
		$row->checkin();

		$msg = JText::_('Successfully Reset Hit count');
		$mainframe->redirect('index.php?option=com_content&sectionid='.$redirect.'&task=edit&id='.$id, $msg);
	}
	
	function getTitleFromFilenameWithoutExt (&$filename) {
	
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		$string = false;
		$string = preg_match( "/\./i", $folder_array[$last_array_value] );
		if ($string) {
			return PhocaDownloadHelper::removeExtension($folder_array[$last_array_value]);
		} else {
			return $folder_array[$last_array_value];
		}
	}
	
	function removeExtension($file_name) {
		return substr($file_name, 0, strrpos( $file_name, '.' ));
	}
	
	function getExtension( $file_name ) {
		return strtolower( substr( strrchr( $file_name, "." ), 1 ) );
	}
	
	
	function getPathSet($item='') {
	
		if ($item == 'icon' || $item == 'iconspec1' || $item == 'iconspec2') {
			$path['orig_abs_ds'] 	= JPATH_ROOT . DS . 'images' . DS . 'phocadownload' . DS ;
			$path['orig_abs'] 		= JPATH_ROOT . DS . 'images' . DS . 'phocadownload' ;
			$path['orig_rel_ds'] 	= '../images/phocadownload/';
		} else {
			// File
			//$paramsC		= &JComponentHelper::getParams( 'com_phocadownload' );
			//$downloadFolder	= $paramsC->get( 'download_folder', 'phocadownload' );
			
			// Absolute path which can be outside public_html
			$absolutePath	= PhocaDownloadHelper::getSettings('absolute_path', '');
			if ($absolutePath != '') {
				$downloadFolder 		= str_replace('/', DS, JPath::clean($absolutePath));
				$path['orig_abs_ds'] 	= $absolutePath . DS ;
				$path['orig_abs'] 		= $absolutePath ;
				
				//$downloadFolderRel 	= str_replace(DS, '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 	= '';
			} else {
				$downloadFolder	= PhocaDownloadHelper::getSettings('download_folder', 'phocadownload' );

				$downloadFolder 		= str_replace('/', DS, JPath::clean($downloadFolder));
				$path['orig_abs_ds'] 	= JPATH_ROOT . DS . $downloadFolder . DS ;
				$path['orig_abs'] 		= JPATH_ROOT . DS . $downloadFolder ;
				
				$downloadFolderRel 	= str_replace(DS, '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 	= '../' . $downloadFolderRel .'/';
			}
		}
		return $path;
	}
	
	function getPhocaVersion()
	{
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_phocadownload';
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.'com_phocadownload';
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	
	function getFileSizeReadable ($size, $retstring = null)//http://aidanlister.com/repos/v/function.size_readable.php
	{
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}
	
	function getTitleFromFilenameWithExt (&$filename) {
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return $folder_array[$last_array_value];
	}

	
	function getMimeType($extension, $params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$returnMime = '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}

			// Return mime if extension call it
			if ($extension == $values[0]) {
				$returnMime = $values[1];
			}
		}

		if ($returnMime != '') {
			return $returnMime;
		} else {
			return "PhocaErrorNoMimeFound";
		}
	}
	
	
	
	function getMimeTypeString($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$extString 	= '';
		$mimeString	= '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
				
			// Create strings
			$extString .= $values[0];
			$mimeString .= $values[1];
			
			$j = $i + 1;
			if ($j < $count_matches) {
				$extString .=',';
				$mimeString .=',';
			}
		}
		
		$string 		= array();
		$string['mime']	= $mimeString;
		$string['ext']	= $extString;
		
		return $string;
	}
	
	function getSettings($title = '', $default = '') {
	
		$db		=& JFactory::getDBO();
		$wheres = array();
		
		if ($title == '') {
			$select	= 'st.*';
			$where	= '';
		} else {
			$select		= 'st.value';
			$wheres[]	= 'st.title =\''.$title.'\'';
			
			$where = " WHERE " . implode( " AND ", $wheres );
		}
		
		
		$query = ' SELECT '.$select
			. ' FROM #__phocadownload_settings AS st'
			. $where
			. ' ORDER BY st.id';
			
		$db->setQuery($query);
		$settings = $db->loadObjectList();
		
		
		// All ITEMS
		if ($title == '') {
			return $settings;
		} else {
			// ONLY ONE ITEM
			if (empty($settings)) {
				return $default;
			} else {
				if (isset($settings[0]->value)) {
					return($settings[0]->value);
				} else {
					return '';
				}
			}
		}
	}
	
	function getSettingsValues($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$values = array();
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
			
			// Create strings
			$returnValues[$i]['id']	= $values[0];
			$returnValues[$i]['value']	= $values[1];
		}

		return $returnValues;
	}
	
	
	function getTextareaSettings($id, $title, $value, $class = 'text_area', $rows = 8, $cols = 50, $style = 'width:300px' ) {
		
		return '<textarea class="'.$class.'" name="phocaset['.$id.']" id="phocaset['.$id.']" rows="'.$rows.'" cols="'.$cols.'" style="'.$style.'" title="'.JText::_( $title . ' DESC' ).'" />'.$value.'</textarea>';
	}
	
	
	function getTextSettings($id, $title, $value, $class = 'text_area', $size = 50, $maxlength = 255, $style = 'width:300px' ) {
		
		return '<input class="'.$class.'" type="text" name="phocaset['.$id.']" id="phocaset['.$id.']" value="'.$value.'" size="'.$size.'" maxlength="'.$maxlength.'" style="'.$style.'" title="'.JText::_( $title . ' DESC' ).'" />';
	}
	
	
	function getSelectSettings($id, $title, $value, $values, $class = 'inputbox', $size = 50, $maxlength = 255, $style = 'width:300px' ) {
		
		$valuesArray = PhocaDownloadHelper::getSettingsValues($values);
		
		$select = '<select name="phocaset['.$id.']" id="phocaset['.$id.']" class="'.$class.'">'. "\n";
		foreach ($valuesArray as $valueOption) {
			if ($value == $valueOption['id']) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			
			$select .= '<option value="'.$valueOption['id'].'" '.$selected.'>'.JText::_($valueOption['value']).'</option>' . "\n";
		}
		$select .= '</select>'. "\n";

		return $select;					
	}
	
	function displayNewIcon ($date, $time = 0) {
		
		if ($time == 0) {
			return '';
		}
		
		$dateAdded 	= strtotime($date, time());
		$dateToday 	= time();
		$dateExists = $dateToday - $dateAdded;
		$dateNew	= $time * 24 * 60 * 60;
		
		if ($dateExists < $dateNew) {
			return '&nbsp;'. JHTML::_('image.site', 'icon-new.png', 'components/com_phocadownload/assets/images/', '','','new');
		} else {
			return '';
		}
	
	}
	
	function getPhocaId($id){
		$v	= PhocaDownloadHelper::getPhocaVersion();
		$i	= str_replace('.', '',substr($v, 0, 3));
		$n	= '<p>&nbsp;</p>';
		$l	= 'h'.'t'.'t'.'p'.':'.'/'.'/'.'w'.'w'.'w'.'.'.'p'.'h'.'o'.'c'.'a'.'.'.'c'.'z'.'/'.'p'.'h'.'o'.'c'.'a'.'d'.'o'.'w'.'n'.'l'.'o'.'a'.'d';
		$t	= 'P'.'o'.'w'.'e'.'r'.'e'.'d'.' '.'b'.'y';
		$p	= 'P'.'h'.'o'.'c'.'a'.' '.'D'.'o'.'w'.'n'.'l'.'o'.'a'.'d';
		$im = 'i'.'c'.'o'.'n'.'-'.'p'.'h'.'o'.'c'.'a'.'-'.'l'.'o'.'g'.'o'.'-'.'s'.'m'.'a'.'l'.'l'.'.'.'p'.'n'.'g';
		$s	= 's'.'t'.'y'.'l'.'e'.'='.'"'.'t'.'e'.'x'.'t'.'-'.'d'.'e'.'c'.'o'.'r'.'a'.'t'.'i'.'o'.'n'.':'.'n'.'o'.'n'.'e'.'"';
		$s2	= 's'.'t'.'y'.'l'.'e'.'='.'"'.'t'.'e'.'x'.'t'.'-'.'a'.'l'.'i'.'g'.'n'.':'.'c'.'e'.'n'.'t'.'e'.'r'.';'.'c'.'o'.'l'.'o'.'r'.':'.'#'.'d'.'3'.'d'.'3'.'d'.'3'.'"';
		$b	= 't'.'a'.'r'.'g'.'e'.'t'.'='.'"'.'_'.'b'.'l'.'a'.'n'.'k'.'"';
		$i	= (int)$i * 2;
		$string	= '';
		if ($id != $i) {
			$string		.= $n;
			$string		.= '<div '.$s2.'>';
		}
		
		if ($id == $i) {
			$string	.= '<!-- <a href="'.$l.'">site: www.phoca.cz | version: '.$v.'</a> -->';
		} else {
			$string	.= $t . ' <a href="'.$l.'" '.$s.' '.$b.' title="'.$p.'">'. $p. '</a>';
		}
		if ($id != $i) {
			$string		.= '</div>' . $n;
		}
		return $string;
	}
	
	function displayHotIcon ($hits, $requiredHits = 0) {
		
		if ($requiredHits == 0) {
			return '';
		}
		
		if ($requiredHits <= $hits) {
			return '&nbsp;'. JHTML::_('image.site', 'icon-hot.png', 'components/com_phocadownload/assets/images/', '','','hot');
		} else {
			return '';
		}
	
	}
}
?>