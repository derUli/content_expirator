<?php
$migrator = new DBMigrator("module/content_expirator", ModuleHelper::buildRessourcePath("content_expirator", "sql/up"));
$migrator->migrate();
