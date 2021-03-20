<?php
namespace Classes;

/**
 * Class Application
 * @package Classes
 */
class Application
{
    protected bool $ApplicationState;
    public const STATE_STOPPED = false;
    public const STATE_STARTED = true;

    public function __construct()
    {
        $this->SetApplicationState(self::STATE_STOPPED);
    }

    /**
     * @param bool $State Set Application state (running)
     */
    protected function SetApplicationState(bool $State) {
        $this->ApplicationState = $State;
    }

    /**
     * @return bool Get current application state (running)
     */
    protected function GetApplicationState(): bool {
        return $this->ApplicationState;
    }

    /**
     * @return bool Application is running
     */
    protected function GetApplicationIsRunning() {
        return ($this->GetApplicationState() === self::STATE_STARTED);
    }
}