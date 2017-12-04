<?php
$migrator = new DBMigrator("module/valid_from_to", ModuleHelper::buildRessourcePath("valid_from_to", "sql/up"));
$migrator->migrate();