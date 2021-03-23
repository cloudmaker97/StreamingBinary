<?php
namespace Classes;
use Configuration\ConfigurationLoader;
use Helper\Output\OutputMessage;

/**
 * Class Bootstrapper
 * @package Classes
 */
class Bootstrapper extends Application {
    /* @var $Modules Module[] */
    private array $Modules = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Starts the application after all modules
     * added to the bootstrapper in the index.php
     */
    public function StartApplication() {
        OutputMessage::create("Anwendung wird gestartet");
        $this->SetApplicationState(self::STATE_STARTED);
        $this->StartModules();
    }

    /**
     * Gets a module by unique module name
     * @param $Name string Unique Module Name
     * @return Module Requested Module
     */
    public function FindModuleByName($Name): Module
    {
        foreach ($this->Modules as $module) {
            if($module->GetModuleName() === $Name) return $module;
        }
    }

    /**
     * Adds a module for execution in application
     * @param Module $Module
     */
    public function AddModule(Module $Module) {
        $this->Modules[] = $Module;
    }

    /**
     * Call all modules for initializing before the
     * modules go in the while Statement ... forever
     */
    private function StartModules(): void {
        OutputMessage::create("Module werden initialisiert");
        foreach ($this->Modules as $module) {
            $module->OnInitialize($this->BundleConfigArguments());
            OutputMessage::create(sprintf("Modul wurde erfolgreich initialisiert: %s", $module->GetModuleName()));
        }

        $this->UpdateModules();
    }

    /**
     * Updates the modules while the application is running
     * after the initialization of each module ... really forever
     * Each step is seperated by one second after last successful
     * execution for security resons (e.g. api limits)
     */
    private function UpdateModules(): void {
        while($this->GetApplicationIsRunning()) {
            foreach ($this->Modules as $module) {
                $module->OnIntervalUpdate($this->BundleConfigArguments());
            }
            sleep(1);
        }
    }

    /**
     * Builds an array of useful data for further usage in modules
     * @return array Bundle of Arguments and Configuration/Settings
     */
    private function BundleConfigArguments(): array
    {
        return [
            'this' => $this,
            'config' => ConfigurationLoader::Create()->GetConfiguration()
        ];
    }
}