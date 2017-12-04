<?php
class ContentExpirator extends Controller {
	private $moduleName = "content_expirator";
	private $moduleTitle = "Content Expirator";
	public function getSettingsHeadline() {
		return $this->moduleTitle;
	}
	public function getSettingsLinkText() {
		return get_translation ( "open" );
	}
	public function settings() {
		return Template::executeModuleTemplate ( $this->moduleName, "list.php" );
	}
	public function cron() {
		BetterCron::minutes ( "module/valid_from_to/update", 10, function () {
			
			$updated = false;
			
			// get all content with valid_from not null or valid_to not null
			// check if page is in valid time and set enabled
			$datasets = ContentWithExpiration::getAll ();
			foreach ( $datasets as $data ) {
				$active = false;
				if ($data->getValidFrom () and $data->getValidUntil ()) {
					$active = (time () >= $data->getValidFrom () and time () < $data->getValidUntil ());
				} else if ($data->getValidFrom ()) {
					$active = (time () >= $data->getValidFrom ());
				} else if ($data->getValidUntil ()) {
					$active = (time () < $data->getValidUntil ());
				}
				if ($active != $data->getActive ()) {
					$updated = true;
					$data->setActive ( $active );
					$data->save ();
				}
				// process data
			}
			// clear cache if pages where enabled or disabled
			// FIXME: clear only cache entry for changed pages
			if ($updated) {
				clearCache ();
			}
		} );
	}
	public function uninstall() {
		$migrator = new DBMigrator ( "module/content_expirator", ModuleHelper::buildRessourcePath ( "content_expirator", "sql/down" ) );
		$migrator->rollback ();
	}
	public function savePost() {
		$id = Request::getVar ( "id", 0, "int" );
		
		$valid_from = Request::getVar ( "valid_from", null, "str" );
		$valid_until = Request::getVar ( "valid_until", null, "str" );
		
		$valid_from = ! empty ( $valid_from ) ? strtotime ( $valid_from ) : null;
		$valid_until = ! empty ( $valid_until ) ? strtotime ( $valid_until ) : null;
		
		$content = new ContentWithExpiration ( $id );
		$content->setValidFrom ( $valid_from );
		$content->setValidUntil ( $valid_until );
		$content->save ();
		Request::redirect ( ModuleHelper::buildAdminURL ( $this->moduleName ) );
	}
}
