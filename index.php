<?php
use Classes\Bootstrapper;
use Modules\SongModule;
use Modules\TimeModule;

// Generous Settings
date_default_timezone_set("Europe/Berlin");

// Interfaces
include "./Interfaces/IModule.php";

// Classes
include "./Helpers/OutputMessage.php";
include "./Config/ApplicationSecrets.php";
include "./Classes/Application.php";
include "./Classes/Module.php";
include "./Modules/TimeModule.php";
include "./Modules/SongModule.php";
include "./Classes/Bootstrapper.php";

// Core Bootstrapper
$bootstrapper = new Bootstrapper();
$bootstrapper->AddModule(new TimeModule());
$bootstrapper->AddModule(new SongModule());
$bootstrapper->StartApplication();