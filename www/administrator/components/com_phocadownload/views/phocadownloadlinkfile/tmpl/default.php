<?php
defined('_JEXEC') or die('Restricted access');
$user 	=& JFactory::getUser();

//Ordering allowed ?
$ordering = ($this->lists['order'] == 'a.ordering');

JHTML::_('behavior.tooltip');
?>
<script type="text/javascript">
//<![CDATA[
function insertLink() {
	var title = document.getElementById("title").value;
	if (title != '') {
		title = "|text="+title;
	}
	var target = document.getElementById("target").value;
	if (target != '') {
		target = "|target="+target;
	}

	
	var fileIdOutput;
	fileIdOutput = '';
	len = document.getElementsByName("fileid").length;
	for (i = 0; i <len; i++) {
		if (document.getElementsByName('fileid')[i].checked) {
			fileid = document.getElementsByName('fileid')[i].value;
			if (fileid != '' && parseInt(fileid) > 0) {
				fileIdOutput = "|id="+fileid;
			} else {
				fileIdOutput = '';
			}
		}
	}
	
	if (fileIdOutput != '' &&  parseInt(fileid) > 0) {
		var tag = "{phocadownload view=file"+fileIdOutput+title+target+"}";
		window.parent.jInsertEditorText(tag, '<?php echo $this->tmpl['ename']; ?>');
		window.parent.document.getElementById('sbox-window').close();
		return false;
	} else {
		alert("<?php echo JText::_( 'You must select a file', true ); ?>");
		return false;
	}
}
//]]>
</script>
<div id="phocadownload-links">
<fieldset class="adminform">
<legend><?php echo JText::_( 'File' ); ?></legend>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm">
	<table class="admintable" width="100%">
		<tr>
			<td class="key" align="right" width="20%">
				<label for="title">
					<?php echo JText::_( 'Filter' ); ?>
				</label>
			</td>
			<td width="80%">
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
		<tr>
			<td class="key" align="right" nowrap="nowrap">
			<label for="title" nowrap="nowrap">
				<?php echo JText::_( 'Section' ); ?>, <?php echo JText::_( 'Category' ); ?>
			</label>
			</td>
			<td>
			<?php
				echo $this->lists['sectionid'];
				echo $this->lists['catid'];
				//echo $this->lists['state'];
				?>
			</td>
		</tr>
	</table>

	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5px"><?php echo JText::_( 'NUM' ); ?></th>
					<th width="5px"></th>
					<th class="title" width="40%"><?php echo JHTML::_('grid.sort',  'Title', 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'Filename', 'a.filename', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'ID', 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
					$row = &$this->items[$i];
					
					
					
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td><?php echo $this->pagination->getRowOffset( $i ); ?></td>
					<td><input type="radio" name="fileid" value="<?php echo $row->id ?>" /></td>
					
					<td><?php echo $row->title; ?></td>
					<td><?php echo $row->filename;?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php
				$k = 1 - $k;
				}
			?>
			</tbody>
		</table>
	</div>

	
<input type="hidden" name="controller" value="phocadownloadlinkfile" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<input type="hidden" name="e_name" value="<?php echo $this->tmpl['ename']?>" />
</form>

<form name="adminFormLink" id="adminFormLink">
<table class="admintable" width="100%">
	<tr >
		<td class="key" align="right">
			<label for="title">
				<?php echo JText::_( 'Title' ); ?>
			</label>
		</td>
		<td>
			<input type="text" id="title" name="title" />
		</td>
	</tr>
	<tr >
		<td class="key" align="right">
			<label for="target">
				<?php echo JText::_( 'Target' ); ?>
			</label>
		</td>
		<td>
			<select name="target" id="target">
			<option value="s" selected="selected"><?php echo JText::_( 'Target _self' ); ?></option>
			<option value="b"><?php echo JText::_( 'Target _blank' ); ?></option>
			<option value="t"><?php echo JText::_( 'Target _top' ); ?></option>
			<option value="p"><?php echo JText::_( 'Target _parent' ); ?></option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td align="right"><button onclick="insertLink();return false;"><?php echo JText::_( 'Insert Link' ); ?></button></td>
	</tr>
</table>
</form>
</fieldset>
<div style="text-align:right;margin:20px 5px;"><span class="icon-16-edb-back"><a style="text-decoration:underline" href="<?php echo $this->tmpl['backlink'];?>"><?php echo JText::_('Back')?></a></span></div>
</div>