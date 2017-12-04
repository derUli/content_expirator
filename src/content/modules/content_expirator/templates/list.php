<?php
$content = ContentFactory::getAll ( "title" );
$acl = new ACL ();
$permissions = getModuleMeta ( "content_expirator", "action_permissions" );
$canEdit = $acl->hasPermission ( $permissions ["content_expirator_edit"] );
?>
<table class="tablesorter">
	<thead>
		<tr>
			<th><?php translate("title");?></th>
			<th><?php translate("language");?></th>
			<th class="hide-on-mobile"><?php translate("enabled");?></th>
			<th class="hide-on-mobile"><?php translate("valid_from");?></th>
			<th class="hide-on-mobile"><?php translate("valid_until");?></th>
			<?php if($canEdit){?>
			<td></td>
			<?php }?>
		</tr>
	</thead>
	<tbody>
		<?php
		
		foreach ( $content as $dataset ) {
			$contentExpiration = new ContentWithExpiration ( $dataset->id );
			?>
		<tr>
			<td><?php esc($dataset->title);?></td>
			<td><?php esc(getLanguageNameByCode($dataset->language));?></td>
			<td class="hide-on-mobile"><?php ($dataset->active ? translate("yes") : translate("no"));?></td>
			<td class="hide-on-mobile"><?php esc($contentExpiration->getValidFrom() ? strftime("%x %R", $contentExpiration->getValidFrom()) : "-");?></td>
			<td class="hide-on-mobile"><?php esc($contentExpiration->getValidUntil() ? strftime("%x %R", $contentExpiration->getValidUntil()) : "-");?></td>
						<?php if($canEdit){?>
			
			<td class="text-center"><a
				href="<?php echo ModuleHelper::buildActionURL("content_expirator_edit", "id={$dataset->id}");?>"><img
					src="gfx/edit.png" alt="<?php translate("edit");?>"
					title="<?php translate("edit");?>"></a></td>
								<?php }?>
					
		</tr>
		<?php }?>
	</tbody>
</table>