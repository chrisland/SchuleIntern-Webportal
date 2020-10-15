<?php

// on module activation
$moduleName = $_GET['module_name'];

@mkdir(__DIR__ . "/www/modules/$moduleName", 0775, true);

copy(__DIR__ . "/modules/$moduleName/build", __DIR__ . "/www/modules/$moduleName");

// ODER
// es liese sich auch über die package.json der "build"-Ordner name auslesen, falls man den ändern wollen würde.. wieso auch immer
$packageJson = file_get_contents(__DIR__ . "/modules/$moduleName/package.json");
$buildDirName = $packageJson['directories']['build'] ?? 'build';

copy(__DIR__ . "/modules/$moduleName/$buildDirName", __DIR__ . "/www/modules/$moduleName");
