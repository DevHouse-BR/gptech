<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
mm_showMyFileName(__FILE__);

$iCol = 1;
if( !isset( $categories_per_row )) {
	$categories_per_row = 4;
}
$cellwidth = intval( 100 / $categories_per_row );

if( empty( $categories )) {
	return; // Do nothing, if there are no child categories!
}
?>
<table width="100%" cellspacing="2" cellpadding="0">
<?php
foreach( $categories as $category ) {
	if ($iCol == 1) { // this is an indicator wether a row needs to be opened or not
		echo "<tr>\n";
	}
	$cat_link = URL."index.php?option=com_virtuemart&amp;page=shop.browse&amp;category_id=".$category["category_id"];
	$cat_name = $category["category_name"];
	?>
	<td valign="top" align="center" width="<?php echo $cellwidth ?>%">
		<div class="module mod-rounded first">
			<!--<h3 class="header">
				<span class="header-2">
					<span class="header-3">
						<span class="icon icon-lock"></span>
						<?=$cat_name?>
					</span>
				</span>
			</h3>
			<div class="badge badge-hot"></div>-->
			<div class="box-t1">
				<div class="box-t2">
					<div class="box-t3"></div>
				</div>
			</div>
			<div style="min-height: 170px;" class="box-1 deepest with-header">
				<a title="<?=$cat_name?>" href="<?=$sess->purl($cat_link)?>">
					<?php
					if ( $category["category_thumb_image"] ) {
						echo ps_product::image_tag( $category["category_thumb_image"], "alt=\"".$category["category_name"]."\"", 0, "category");
					}
					?>
				</a>
				<br />
				<br/>
				<a title="<?=$cat_name?>" style="font-weight:bold;color:#000000;text-transform:uppercase" href="<?=$sess->purl($cat_link)?>">
					<?=$cat_name?>
				</a>
				<br/>
				<br/><br/>
				<?
				$query = "SELECT category_name, category_thumb_image FROM #__{vm}_category as c, #__{vm}_category_xref as xref WHERE c.category_publish='Y' AND xref.category_parent_id=" . $category["category_id"] . " AND c.category_id=xref.category_child_id ORDER BY list_order, category_name ASC";
				$db = new ps_DB();
				$db->query( $query );
				
				while( $db->next_record() ) {
					?><a style="color:#da211d" href="<?=$sess->purl(URL."index.php?option=com_virtuemart&amp;page=shop.browse&amp;category_id=".$category["category_id"])?>"><?=$db->f("category_name")?></a> <?
				}
				?>
			</div>
			<div class="box-b1">
				<div class="box-b2">
					<div class="box-b3"></div>
				</div>
			</div>
		</div>
	</td>
	
	
	<?php
	// Do we need to close the current row now?
	if ($iCol == $categories_per_row) { // If the number of products per row has been reached
		echo "</tr>\n";
		$iCol = 1;
	}
	else {
		$iCol++;
	}
}
// Do we need a final closing row tag?
if ($iCol != 1) {
	echo "</tr>\n";
}
?>
</table>