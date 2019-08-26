<script src="/<?=ADMIN_DIR?>/js/catalogSimple.js" type="text/javascript"></script>

<h1>Товары</h1>

<div id="items" style="display: none; "> Загрузка....</div>

<script>
$(document).ready(function(){
	CatalogSimple.ITEMS_OPTS.noTop = true;
	CatalogSimple.ITEMS_OPTS.status='<?=$_REQUEST['status']?>';
	
	CatalogSimple.itemsList();
});
</script>






<!--форма редактирования-->
<div id="float" class="view " style="min-width: 1000px; max-width: 1200px;" >!!</div>

<iframe name="frame8" style="display: none; "></iframe>