<?php
class ContentWithExpiration extends Model {
	private $valid_from;
	private $valid_until;
	private $active = false;
	public function loadByID($id) {
		$query = Database::pQuery ( "select id, UNIX_TIMESTAMP(valid_from) as valid_from, UNIX_TIMESTAMP(valid_until) as valid_until, active from `{prefix}content` where id = ?", array (
				intval ( $id ) 
		), true );
		if (Database::getNumRows ( $query ) > 0) {
			$this->fillVars ( $query );
		} else {
			$this->fillVars ( null );
		}
	}
	protected function fillVars($query = null) {
		if ($query) {
			$result = Database::fetchObject ( $query );
			$this->setID ( $result->id );
			$this->setActive ( boolval ( $result->active ) );
			$this->setValidFrom ( $result->valid_from );
			$this->setValidUntil ( $result->valid_until );
		} else {
			$this->setID ( null );
			$this->setActive ( false );
			$this->setValidFrom ( null );
			$this->setValidUntil ( null );
		}
	}
	public function getValidFrom() {
		return $this->valid_from;
	}
	public function getValidUntil() {
		return $this->valid_until;
	}
	public function getActive() {
		return $this->active;
	}
	public function getRealContent() {
		return ContentFactory::getByID ( $this->id );
	}
	public function setActive($val) {
		$this->active = boolval ( $val );
	}
	public function setValidFrom($time) {
		$this->valid_from = ! is_null ( $time ) ? intval ( $time ) : null;
	}
	public function setValidUntil($time) {
		$this->valid_until = ! is_null ( $time ) ? intval ( $time ) : null;
	}
	public static function getAll($order = "id") {
		$query = Database::pQuery ( "select id from `{prefix}content` where valid_from is not null or valid_until is not null order by $order", array (), true );
		$results = array ();
		while ( $row = Database::fetchobject ( $query ) ) {
			$results [] = new ContentWithExpiration ( $row->id );
		}
		return $results;
	}
	public function update() {
		// TODO: Convert to MySQL Datetime String
		Database::pQuery ( "update `{prefix}content` set valid_from = FROM_UNIXTIME(?), valid_until = FROM_UNIXTIME(?), active = ? where id = ?", array (
				$this->getValidFrom (),
				$this->getValidUntil (),
				$this->getActive (),
				$this->getID () 
		), true );
	}
}