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

class PhocaDownloadCpControllerPhocaDownloadinstall extends PhocaDownloadCpController
{
	function __construct(){
		parent::__construct();
		$this->registerTask( 'install'  , 'install' );
		$this->registerTask( 'upgrade'  , 'upgrade' );		
	}
	
	function install() {		
		$db		=& JFactory::getDBO();
		$dbPref = $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		// --------------------------------------------------------------------------
		
		$query =' DROP TABLE IF EXISTS `'.$dbPref.'phocadownload_categories`;';
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query ='CREATE TABLE `'.$dbPref.'phocadownload_categories` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `parent_id` int(11) NOT NULL default 0,'."\n";
		$query.='  `section` int(11) NOT NULL default 0,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `name` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image_position` varchar(30) NOT NULL default \'\','."\n";
		$query.='  `description` text,'."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `editor` varchar(50) default NULL,'."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `access` tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `cat_idx` (`section`,`published`,`access`),'."\n";
		$query.='  KEY `idx_access` (`access`),'."\n";
		$query.='  KEY `idx_checkout` (`checked_out`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocadownload_sections`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE `'.$dbPref.'phocadownload_sections` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `name` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image` text,'."\n";
		$query.='  `scope` varchar(50) NOT NULL default \'\','."\n";
		$query.='  `image_position` varchar(30) NOT NULL default \'\','."\n";
		$query.='  `description` text,'."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `access` tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `idx_scope` (`scope`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocadownload`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE `'.$dbPref.'phocadownload` ('."\n";
		$query.='  `id` int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.='  `catid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `sectionid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `sid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `title` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `filename` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `filename_play` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `filename_preview` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `author` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `author_email` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `author_url` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `license` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `license_url` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image_filename` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image_filename_spec1` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image_filename_spec2` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `image_download` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `link_external` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `description` text,'."\n";
		$query.='  `version` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `directlink` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `hits` int(11) NOT NULL default \'0\','."\n";
		$query.='  `textonly` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `checked_out` int(11) NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  `access` tinyint(3) unsigned NOT NULL default \'0\','."\n";
		$query.='  `confirm_license` int(11) NOT NULL default \'0\','."\n";
		$query.='  `unaccessible_file` int(11) NOT NULL default \'0\','."\n";
		$query.='  `params` text,'."\n";
		$query.='  PRIMARY KEY  (`id`),'."\n";
		$query.='  KEY `catid` (`catid`,`published`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocadownload_settings`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query =' CREATE TABLE `'.$dbPref.'phocadownload_settings` ('."\n";
		$query.='  `id` int(11) unsigned NOT NULL auto_increment,'."\n";
		$query.='  `title` varchar(250) NOT NULL default \'\','."\n";
		$query.='  `value` text,'."\n";
		$query.='  `values` text,'."\n";
		$query.='  `type` varchar(50) NOT NULL default \'\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// VALUES
		
		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'download_folder', 'phocadownload','', 'text');"."\n";
		
		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'allowed_file_types', '{hqx=application/mac-binhex40}\n{cpt=application/mac-compactpro}\n{csv=text/x-comma-separated-values}\n{bin=application/macbinary}\n{dms=application/octet-stream}\n{lha=application/octet-stream}\n{lzh=application/octet-stream}\n{exe=application/octet-stream}\n{class=application/octet-stream}\n{psd=application/x-photoshop}\n{so=application/octet-stream}\n{sea=application/octet-stream}\n{dll=application/octet-stream}\n{oda=application/oda}\n{pdf=application/pdf}\n{ai=application/postscript}\n{eps=application/postscript}\n{ps=application/postscript}\n{smi=application/smil}\n{smil=application/smil}\n{mif=application/vnd.mif}\n{xls=application/vnd.ms-excel}\n{ppt=application/powerpoint}\n{wbxml=application/wbxml}\n{wmlc=application/wmlc}\n{dcr=application/x-director}\n{dir=application/x-director}\n{dxr=application/x-director}\n{dvi=application/x-dvi}\n{gtar=application/x-gtar}\n{gz=application/x-gzip}\n{php=application/x-httpd-php}\n{php4=application/x-httpd-php}\n{php3=application/x-httpd-php}\n{phtml=application/x-httpd-php}\n{phps=application/x-httpd-php-source}\n{js=application/x-javascript}\n{swf=application/x-shockwave-flash}\n{sit=application/x-stuffit}\n{tar=application/x-tar}\n{tgz=application/x-tar}\n{xhtml=application/xhtml+xml}\n{xht=application/xhtml+xml}\n{zip=application/x-zip}\n{mid=audio/midi}\n{midi=audio/midi}\n{mpga=audio/mpeg}\n{mp2=audio/mpeg}\n{mp3=audio/mpeg}\n{aif=audio/x-aiff}\n{aiff=audio/x-aiff}\n{aifc=audio/x-aiff}\n{ram=audio/x-pn-realaudio}\n{rm=audio/x-pn-realaudio}\n{rpm=audio/x-pn-realaudio-plugin}\n{ra=audio/x-realaudio}\n{rv=video/vnd.rn-realvideo}\n{wav=audio/x-wav}\n{bmp=image/bmp}\n{gif=image/gif}\n{jpeg=image/jpeg}\n{jpg=image/jpeg}\n{jpe=image/jpeg}\n{png=image/png}\n{tiff=image/tiff}\n{tif=image/tiff}\n{css=text/css}\n{html=text/html}\n{htm=text/html}\n{shtml=text/html}\n{txt=text/plain}\n{text=text/plain}\n{log=text/plain}\n{rtx=text/richtext}\n{rtf=text/rtf}\n{xml=text/xml}\n{xsl=text/xml}\n{mpeg=video/mpeg}\n{mpg=video/mpeg}\n{mpe=video/mpeg}\n{qt=video/quicktime}\n{mov=video/quicktime}\n{avi=video/x-msvideo}\n{flv=video/x-flv}\n{movie=video/x-sgi-movie}\n{doc=application/msword}\n{xl=application/excel}\n{eml=message/rfc822}', '', 'textarea');"."\n";

		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'disallowed_file_types', '','', 'textarea');"."\n";
		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'upload_maxsize', '3000000','', 'text');"."\n";
		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'enable_flash', 0,'{0=No}{1=Yes}', 'select');"."\n";
		
		// Version 1.0.6
		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'enable_user_statistics', 1,'{0=No}{1=Yes}', 'select');"."\n";
		// Version 1.1.0
		$queries[] = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'absolute_path', '','', 'text');"."\n";
		
		foreach ($queries as $valueQuery) {
			$db->setQuery( $valueQuery );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}

		// --------------------------------------------------------------------------
		
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocadownload_user_stat`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query='CREATE TABLE `'.$dbPref.'phocadownload_user_stat` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `fileid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `userid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
	
		// --------------------------------------------------------------------------
	
		$query=' DROP TABLE IF EXISTS `'.$dbPref.'phocadownload_licenses`;'."\n";
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		$query ='CREATE TABLE `'.$dbPref.'phocadownload_licenses` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `description` text,'."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// END -------------------------------------------------------------------------------
		

		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}
		
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Download not successfully installed' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Download successfully installed' );
		}
	
		
		$link = 'index.php?option=com_phocadownload';
		$this->setRedirect($link, $msg);
	}
	
	function upgrade()
	{
		$db			=& JFactory::getDBO();
		$dbPref 	= $db->getPrefix();
		$msgSQL 	= '';
		$msgFile	= '';
		$msgError	= '';
		
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum())
		{
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		
		$query=' SELECT * FROM `'.$dbPref.'phocadownload_categories` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum())
		{
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query=' SELECT * FROM `'.$dbPref.'phocadownload_sections` LIMIT 1;'."\n";
		
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum())
		{
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		// UPGRADE PHOCA DOWNLOAD 1.0.5 VERSION
		// ------------------------------------------
		// PHOCADOWNLOAD
		// ------------------------------------------
		$updateDirectLink = false;
		$updateDirectLink = $this->AddColumnIfNotExists("".$dbPref."phocadownload", "directlink", "TINYINT(1) NOT NULL default '0'", "version" );
		if (!$updateDirectLink) {
			$msgSQL .= 'Error while updating DIRECTLINK column';
		}
		
		// UPGRADE PHOCA DOWNLOAD 1.0.6 VERSION
		// ------------------------------------------
		// PHOCADOWNLOAD USER STAT
		// ------------------------------------------
		
		$query='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocadownload_user_stat` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `fileid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `userid` int(11) NOT NULL default \'0\','."\n";
		$query.='  `count` int(11) NOT NULL default \'0\','."\n";
		$query.='  `date` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		
		$query=' SELECT title FROM `'.$dbPref.'phocadownload_settings` WHERE title = \'enable_user_statistics\' LIMIT 1;'."\n";
		$db->setQuery($query);

		if (!$result = $db->loadObject()) {
			$query = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'enable_user_statistics', 1,'{0=No}{1=Yes}', 'select');"."\n";
			$db->setQuery( $query );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
		
		// --------------------------------------------------------------------------
		
		// UPGRADE PHOCA DOWNLOAD 1.1.0 VERSION
		// ------------------------------------------
		// PHOCADOWNLOAD USER STAT
		// ------------------------------------------
		
		$query='CREATE TABLE IF NOT EXISTS `'.$dbPref.'phocadownload_licenses` ('."\n";
		$query.='  `id` int(11) NOT NULL auto_increment,'."\n";
		$query.='  `title` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `alias` varchar(255) NOT NULL default \'\','."\n";
		$query.='  `description` text,'."\n";
		$query.='  `checked_out` int(11) unsigned NOT NULL default \'0\','."\n";
		$query.='  `checked_out_time` datetime NOT NULL default \'0000-00-00 00:00:00\','."\n";
		$query.='  `published` tinyint(1) NOT NULL default \'0\','."\n";
		$query.='  `ordering` int(11) NOT NULL default \'0\','."\n";
		$query.='  PRIMARY KEY  (`id`)'."\n";
		$query.=') TYPE=MyISAM CHARACTER SET `utf8`;';
		
		$db->setQuery( $query );
		if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		
		// --------------------------------------------------------------------------
		
		// ------------------------------------------
		// PHOCADOWNLOAD UPDATE confirm_license
		// ------------------------------------------
		$updateCL 	= false;
		$updateCL	= $this->AddColumnIfNotExists("".$dbPref."phocadownload", "confirm_license", "int(11) NOT NULL default '0'", "access" );
		if (!$updateCL) {
			$msgSQL .= 'Error while updating Confirm License column<br />';
		}
		
		// ------------------------------------------
		// PHOCADOWNLOAD UPDATE confirm_license
		// ------------------------------------------
		$updateUF	= false;
		$updateUF	= $this->AddColumnIfNotExists("".$dbPref."phocadownload", "unaccessible_file", "int(11) NOT NULL default '0'", "access" );
		if (!$updateUF) {
			$msgSQL .= 'Error while updating Display Unaccessible Files column <br />';
		}
		
		// ------------------------------------------
		// PHOCADOWNLOAD CATEGORIES UPDATE date
		// ------------------------------------------
		$updateCD	= false;
		$updateCD	= $this->AddColumnIfNotExists("".$dbPref."phocadownload_categories", "date", "datetime NOT NULL default '0000-00-00 00:00:00'", "access" );
		if (!$updateCD) {
			$msgSQL .= 'Error while updating Date column (categories) <br />';
		}
		
		// ------------------------------------------
		// PHOCADOWNLOAD SECTIONS UPDATE date
		// ------------------------------------------
		$updateSD	= false;
		$updateSD	= $this->AddColumnIfNotExists("".$dbPref."phocadownload_sections", "date", "datetime NOT NULL default '0000-00-00 00:00:00'", "access" );
		if (!$updateSD) {
			$msgSQL .= 'Error while updating Date column (sections) <br />';
		}
		// ------------------------------------------
		// PHOCADOWNLOAD SETTINGS UPDATE absolute_path
		// ------------------------------------------
		
		$query=' SELECT title FROM `'.$dbPref.'phocadownload_settings` WHERE title = \'absolute_path\' LIMIT 1;'."\n";
		$db->setQuery($query);

		if (!$result = $db->loadObject()) {
			$query = "INSERT INTO `".$dbPref."phocadownload_settings` VALUES (null, 'absolute_path', '','', 'text');"."\n";
			$db->setQuery( $query );
			if (!$result = $db->query()){$msgSQL .= $db->stderr() . '<br />';}
		}
		
		// ------------------------------------------
		// PHOCA DOWNLOAD UPDATE 1.2.0
		// ------------------------------------------
		
		// Filename_preview
		$updateFPR	= false;
		$updateFPR	= $this->AddColumnIfNotExists("".$dbPref."phocadownload", "filename_preview", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateFPR) {
			$msgSQL .= 'Error while updating Filename Preview column<br />';
		}
		
		// Filename_play
		$updateFPL	= false;
		$updateFPL	= $this->AddColumnIfNotExists("".$dbPref."phocadownload", "filename_play", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateFPL) {
			$msgSQL .= 'Error while updating Filename Play column<br />';
		}
		
		$updateIFS1	= false;
		$updateIFS1	= $this->AddColumnIfNotExists("".$dbPref."phocadownload", "image_filename_spec1", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateIFS1) {
			$msgSQL .= 'Error while updating Image Filename Spec1 column<br />';
		}
		
		$updateIFS2	= false;
		$updateIFS2	= $this->AddColumnIfNotExists("".$dbPref."phocadownload", "image_filename_spec2", "varchar(250) NOT NULL default ''", "filename" );
		if (!$updateIFS2) {
			$msgSQL .= 'Error while updating Image Filename Spec2 column<br />';
		}
		
		
		// CHECK TABLES
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload_categories` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload_sections` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload_settings` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload_user_stat` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		
		$query =' SELECT * FROM `'.$dbPref.'phocadownload_licenses` LIMIT 1;';
		$db->setQuery( $query );
		$result = $db->loadResult();
		if ($db->getErrorNum()) {
			$msgSQL .= $db->getErrorMsg(). '<br />';
		}
		// - - - - - - - - - - - - - - - - 
		
		// Error
		if ($msgSQL !='') {
			$msgError .= '<br />' . $msgSQL;
		}
		if ($msgFile !='') {
			$msgError .= '<br />' . $msgFile;
		}
			
		// End Message
		if ($msgError !='') {
			$msg = JText::_( 'Phoca Download not successfully upgraded' ) . ': ' . $msgError;
		} else {
			$msg = JText::_( 'Phoca Download successfully upgraded' );
		}
		
		$link = 'index.php?option=com_phocadownload';
		$this->setRedirect($link, $msg);
	}
	
	function AddColumnIfNotExists($table, $column, $attributes = "INT( 11 ) NOT NULL DEFAULT '0'", $after = '' ) {
		
		global $mainframe;
		$db				=& JFactory::getDBO();
		$columnExists 	= false;

		$query = 'SHOW COLUMNS FROM '.$table;
		$db->setQuery( $query );
		if (!$result = $db->query()){return false;}
		$columnData = $db->loadObjectList();
		
		
		foreach ($columnData as $valueColumn) {
			if ($valueColumn->Field == $column) {
				$columnExists = true;
				break;
			}
		}
		echo $column;
		
		if (!$columnExists) {
			if ($after != '') {
				$query = "ALTER TABLE `".$table."` ADD `".$column."` ".$attributes." AFTER `".$after."`";
			} else {
				$query = "ALTER TABLE `".$table."` ADD `".$column."` ".$attributes."";
			}
			$db->setQuery( $query );
			if (!$result = $db->query()){return false;}
		}
		
		return true;
	}
}
// utf-8 test: ä,ö,ü,ø,ž
?>