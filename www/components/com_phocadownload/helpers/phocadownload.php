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

class PhocaDownloadHelperFront
{	
	function download($fileData, $downloadId, $currentLink, $paramsTmpl) {
			
		global $mainframe;
		$directLink 	= $fileData['directlink'];// Direct Link 0 or 1
		$absOrRelFile	= $fileData['file'];// Relative Path or Absolute Path
		
		// NO FILES FOUND (abs file)
		$error = false;
		$error = preg_match("/PhocaError/i", $absOrRelFile);
		
		if ($error) {
			$msg = JText::_('Error while downloading file');
			$mainframe->redirect($currentLink, $msg);
		} else {
			
			// Get extensions
			$extension = JFile::getExt($absOrRelFile);
			
			// Get Mime from params ( ext --> mime)
			$allowedMimeType = PhocaDownloadHelper::getMimeType($extension, $paramsTmpl['allowed_file_types']);
			$disallowedMimeType = PhocaDownloadHelper::getMimeType($extension, $paramsTmpl['disallowed_file_types']);
			
			
			// NO MIME FOUND
			$errorAllowed 		= false;// !!! IF YES - Disallow Downloading
			$errorDisallowed 	= false;// !!! IF YES - Allow Downloading
			
			$errorAllowed 		= preg_match("/PhocaError/i", $allowedMimeType);
			$errorDisallowed	= preg_match("/PhocaError/i", $disallowedMimeType);
			
			if ($errorAllowed) {
				$msg = JText::_('Error while downloading file (Mime Type not found)');
				$mainframe->redirect($currentLink, $msg);
			} else if (!$errorDisallowed) {
				$msg = JText::_('Error while downloading file (Disallowed Mime Type)');
				$mainframe->redirect($currentLink, $msg);
			
			} else {
				if ($directLink == 1) {
					
					$addHit	= PhocaDownloadHelperFront::_hit($downloadId);
					$mainframe->redirect ($absOrRelFile);
					exit;
				} else {
				
					// Clears file status cache
					clearstatcache();
					
					// HIT Statistics
					$addHit	= PhocaDownloadHelperFront::_hit($downloadId);
					
					// USER Statistics
					if ($paramsTmpl['enable_user_statistics'] == 1) {
						$addUserStat = PhocaUserStatHelper::createUserStatEntry($downloadId);
					}
				
					$fileWithoutPath	= basename($absOrRelFile);
					$fileSize 			= filesize($absOrRelFile);
					$mimeType			= '';
					$mimeType			= $allowedMimeType;
					// Clean the output buffer
					ob_end_clean();
					
					header("Cache-Control: public, must-revalidate");
					header('Cache-Control: pre-check=0, post-check=0, max-age=0');
					header("Pragma: no-cache");
					header("Expires: 0"); 
					header("Content-Description: File Transfer");
					header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
					header("Content-Type: " . (string)$mimeType);
					header("Content-Length: ". (string)$fileSize);
					header('Content-Disposition: attachment; filename="'.$fileWithoutPath.'"');
					header("Content-Transfer-Encoding: binary\n");
					
					@readfile($absOrRelFile);
					exit;
				}
			}
			
		}
		return false;
	
	}
	
	function _hit($id) {
		global $mainframe;
		$table = & JTable::getInstance('PhocaDownload', 'Table');
		$table->hit($id);
		return true;
	}
	
	function getOrderingText ($ordering) {
		switch ((int)$ordering) {
			case 2:
				$orderingOutput	= 'ordering DESC';
			break;
			
			case 3:
				$orderingOutput	= 'title ASC';
			break;
			
			case 4:
				$orderingOutput	= 'title DESC';
			break;
			
			case 5:
				$orderingOutput	= 'date ASC';
			break;
			
			case 6:
				$orderingOutput	= 'date DESC';
			break;
			
			case 7:
				$orderingOutput	= 'id ASC';
			break;
			
			case 8:
				$orderingOutput	= 'id DESC';
			break;
		
			case 1:
			default:
				$orderingOutput = 'ordering ASC';
			break;
		}
		return $orderingOutput;
	}
}


jimport('joomla.html.pagination');
class PhocaPagination extends JPagination
{

	function getLimitBox()
	{
		global $mainframe;
		
		$paramsC 			= JComponentHelper::getParams('com_phocadownload') ;
		$pagination 		= $paramsC->get( 'pagination', '5;10;15;20;50;100' );
		$paginationArray	= explode( ';', $pagination );
		
		// Initialize variables
		$limits = array ();

		foreach ($paginationArray as $paginationValue) {
			$limits[] = JHTML::_('select.option', $paginationValue);
		}
		$limits[] = JHTML::_('select.option', '0', JText::_('all'));

		$selected = $this->_viewall ? 0 : $this->limit;

		// Build the select list
		if ($mainframe->isAdmin()) {
			$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="inputbox" size="1" onchange="submitform();"', 'value', 'text', $selected);
		} else {
			$html = JHTML::_('select.genericlist',  $limits, 'limit', 'class="inputbox" size="1" onchange="this.form.submit()"', 'value', 'text', $selected);
		}
		return $html;
	}
}
?>