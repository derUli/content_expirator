<?php
$id = Request::getVar("id", 0, "int");
$content = new ContentWithExpiration($id);
if ($content->getID()) {
    $realContent = $content->getRealContent();
    ?>
<?php echo ModuleHelper::buildMethodCallForm("ContentExpirator", "save", array("id"=>$id));?>
<p>
	<strong><?php translate("title");?></strong><br />
	<?php esc($realContent->title);?>
</p>
<p>
	<strong><?php translate("language");?></strong><br />
	<?php esc(getLanguageNameByCode($realContent->language));?>
</p>
<p>
	<strong><?php translate("enabled");?></strong><br />
	<?php ($content->getActive() ? translate("yes") : translate("no"));?>
</p>
<p>
	<strong><?php translate("valid_from");?></strong><br /> <input
		type="datetime-local" name="valid_from"
		value="<?php echo ($content->getValidFrom() ? date("Y-m-d\TH:i:s", $content->getValidFrom()) : "")?>">
</p>
<p>
	<strong><?php translate("valid_until");?></strong><br /> <input
		type="datetime-local" name="valid_until"
		value="<?php echo ($content->getValidUntil() ? date("Y-m-d\TH:i:s", $content->getValidUntil()) : "")?>">
</p>

<button type="submit" class="btn btn-success"><?php translate("save");?></button>
<?php echo ModuleHelper::endForm();?>

<?php
} else {
    Request::javascriptRedirect(ModuleHelper::buildAdminURL("content_expirator"));
}
?>