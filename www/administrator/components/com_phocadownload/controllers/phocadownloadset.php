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

class PhocaDownloadCpControllerPhocaDownloadset extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
		
		$this->registerTask( 'apply'  , 'save' );
	}

	function save() {
		$post					= JRequest::get('post');
		$phocaSet				= JRequest::getVar( 'phocaset', array(0), 'post', 'array' );

		$model = $this->getModel( 'phocadownloadset' );
		
		switch ( JRequest::getCmd('task') ) {
			case 'apply':
				
				if ($model->store($phocaSet)) {
					$msg = JText::_( 'Changes to Phoca Download Settings Saved' );
				} else {
					$msg = JText::_( 'Error Saving Phoca Download Settings' );
				}
				$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadset', $msg );
				break;

			case 'save':
			default:
				if ($model->store($phocaSet)) {
					$msg = JText::_( 'Phoca Download Settings Saved' );
				} else {
					$msg = JText::_( 'Error Saving Phoca Download Settings' );
				}
				$this->setRedirect( 'index.php?option=com_phocadownload', $msg );
				break;
		}
		// Check the table in so it can be edited.... we are done with it anyway
		$model->checkin();
	}
	
	
	function cancel() {
		$model = $this->getModel( 'phocadownload' );
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_phocadownload' );
	}
}
?>
