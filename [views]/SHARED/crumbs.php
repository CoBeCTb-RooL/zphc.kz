<?php
$crumbs = $MODEL; 
//vd($crumbs);
?>


<?php 
if(count($crumbs))
{?>
	<div class="crumbs">
		<span class="item"><?=join('</span><span class="item">', $crumbs)?></span>
	</div>
<?php 
}?>