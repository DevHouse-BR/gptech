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
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');

class PhocaDownloadViewFile extends JView
{

	function display($tpl = null){		
		global $mainframe;
		
		jimport( 'joomla.filesystem.folder' ); 
		jimport( 'joomla.filesystem.file' );
		
		$params 		= &$mainframe->getParams();
		$uri 			= &JFactory::getURI();
		$model			= &$this->getModel();
		$document		= &JFactory::getDocument();
		//$categoryId		= JRequest::getVar('catid', 0, '', 'int');
		$fileId			= JRequest::getVar('id', 0, '', 'int');
		$limitStart		= JRequest::getVar( 'start', 0, '', 'int');// we need it for category back link
		$category		= $model->getCategory($fileId, $params);
		$file			= $model->getDocument($fileId, $params);
		$tmpl			= array();
		
		
	
		$tmpl['limitstart'] = $limitStart;
		if ($limitStart > 0 ) {
			$tmpl['limitstarturl'] = '&start='.$limitStart;
		} else {
			$tmpl['limitstarturl'] = '';
		}
		
		
		$css						= $params->get( 'theme', 'phocadownload-grey' );
		$tmpl['licenseboxheight']	= $params->get( 'license_box_height', 300 );
		$document->addStyleSheet(JURI::base(true).'/components/com_phocadownload/assets/'.$css.'.css');
	
		$js				= 'var enableDownloadButton = 0;'
						 .'function enableDownload() {'
						 .' if (enableDownloadButton == 0) {'
						 .'   document.forms[\'phocadownloadform\'].elements[\'pdlicensesubmit\'].disabled=false;'
						 .'   enableDownloadButton = 1;'
						 .' } else {'
						 .'   document.forms[\'phocadownloadform\'].elements[\'pdlicensesubmit\'].disabled=true;'
						 .'   enableDownloadButton = 0;'
						 .' }'
						 .'}';
		$document->addScriptDeclaration($js);
		
		// PARAMS
		$tmpl['filename_or_name'] 		= $params->get( 'filename_or_name', 'filename' );
		$tmpl['display_up_icon'] 	= $params->get( 'display_up_icon', 1 );
		$tmpl['allowed_file_types']		= PhocaDownloadHelper::getSettings( 'allowed_file_types', '' );
		$tmpl['disallowed_file_types']	= PhocaDownloadHelper::getSettings( 'disallowed_file_types', '' );
		$tmpl['enable_user_statistics']	= PhocaDownloadHelper::getSettings( 'enable_user_statistics', 1 );
		$tmpl['id']		= PhocaDownloadHelper::getPhocaId($params->get( 'display_id', 1 ));

		// DOWNLOAD
		// - - - - - - - - - - - - - - - 
		$download				= JRequest::getVar( 'download', array(0), '', 'array' );
		$licenseAgree			= JRequest::getVar( 'license_agree', '', 'post', 'string' );
		$downloadId		 		= (int) $download[0];
		
		
		
		
		
		if ($downloadId > 0) {
			
			$currentLink	= 'index.php?option=com_phocadownload&view=file&id='.$file[0]->id.':'.$file[0]->alias. $tmpl['limitstarturl'] . '&Itemid='. JRequest::getVar('Itemid', 1, 'get', 'int');
			
		
			
			// Check Token
			$token	= JUtility::getToken();
			if (!JRequest::getInt( $token, 0, 'post' )) {
				//JError::raiseError(403, 'Request Forbidden');
				$mainframe->redirect(JRoute::_('index.php', false), JText::_("Form data is not valid"));
				exit;
			}
			
			// Check License Agreement
			if (empty($licenseAgree)) {
				$mainframe->redirect(JRoute::_($currentLink, false), JText::_("You must agree to listed terms"));
				exit;
			}
			
			$fileData	= $model->getDownload($downloadId);
			
			PhocaDownloadHelperFront::download($fileData, $downloadId, $currentLink, $tmpl);
			
		}
		// - - - - - - - - - - - - - - - 
		
		// CSS Image Path
		$imagePath		= PhocaDownloadHelper::getPathSet('icon');
		$cssImagePath	= str_replace ( '../', JURI::base(true).'/', $imagePath['orig_rel_ds']);
		
		$filePath		= PhocaDownloadHelper::getPathSet('file');
		


		$this->assignRef('tmpl',			$tmpl);
		
		$this->assignRef('category',		$category);
		$this->assignRef('file',			$file);
		$this->assignRef('params',			$params);
		$this->assignRef('cssimagepath',	$cssImagePath);
		$this->assignRef('absfilepath',		$filePath['orig_abs_ds']);
		$this->assignRef('request_url',		$uri->toString());
		parent::display($tpl);
		
	}
}
?>