<?php
namespace Classes;
use Helper\Output\OutputMessage;
use Interfaces\IModule;

/**
 * Class Module
 * @package Classes
 */
abstract class Module implements IModule
{
    private string $_Name;
    private array $_Settings;
    private int $_PlannedSkipCount = 0;
    private int $_AlreadySkippedCount = 0;

    public function __construct() {}

    /**
     * Set the planned skips for the current module
     * @param $SkipCount int n-Skips
     */
    protected function SetPlannedSkipCount($SkipCount): void {
        $this->_PlannedSkipCount = $SkipCount;
    }

    /**
     * Get if skipping is enabled for the current module
     * @return bool Skippable module
     */
    private function GetIsSkipIntervalEnabled(): bool
    {
        return ($this->_PlannedSkipCount !== 0 && $this->_PlannedSkipCount > 0);
    }

    /**
     * Get if the current request is a non-skippable interval
     * @return bool Execute the Module Code
     */
    protected function GetIsInactiveSkip(): bool
    {
        if($this->GetIsSkipIntervalEnabled() && $this->_PlannedSkipCount == $this->_AlreadySkippedCount) {
            $this->ResetAlreadySkippedCount();
            return true;
        } else {
            OutputMessage::create(sprintf("Überspringen des Moduls wurde erzwungen: %s", $this->GetModuleName()));
            $this->IncrementAlreadySkippedCount();
            return false;
        }
    }

    /**
     * Increments the counter for already skipped intervals
     */
    protected function IncrementAlreadySkippedCount(): void {
        $this->_AlreadySkippedCount++;
    }

    /**
     * Resets the counter for already skipped intervals
     */
    protected function ResetAlreadySkippedCount() {
        $this->_AlreadySkippedCount = 0;
    }

    /**
     * Sets the Name of the Module
     * @param string $Name Module-Name
     */
    public function SetModuleName(string $Name): void {
        $this->_Name = $Name;
    }

    /**
     * Gets the Name of the Module
     * @return string Module-Name
     */
    public function GetModuleName(): string {
        return $this->_Name;
    }

    /**
     * Sets all module settings (any maybe overwrites)
     * @param array $Settings Set all module Settings
     */
    public function SetSettings(array $Settings): void
    {
        $this->_Settings = $Settings;
    }

    /**
     * Get all module settings
     * @return array Get all module Settings
     */
    public function GetSettings(): array
    {
        return $this->_Settings;
    }

    /**
     * @param $Key string Key of the Setting
     * @param $Value mixed Value of the Setting
     */
    public function SetSettingsValue(string $Key, $Value)
    {
        $this->_Settings[$Key] = $Value;
    }

    /**
     * @param $Key string Requested Key of the Setting
     * @return mixed Settings Value
     */
    public function GetSettingsValue(string $Key)
    {
        return $this->_Settings[$Key];
    }

    /**
     * OnInitialize gets called before the application
     * goes in determined condition for interval updates
     * @param $args mixed Arguments for further usage
     */
    abstract function OnInitialize($args) : void;

    /**
     * OnIntervalUpdate gets called on each update
     * sequence of the while condition.
     * @param $args mixed Arguments for further usage
     */
    abstract function OnIntervalUpdate($args) : void;
}