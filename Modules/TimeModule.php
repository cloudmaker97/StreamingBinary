<?php
namespace Modules;
use Classes\Module;
use Exception;

class TimeModule extends Module
{
    private \DateTime $_CurrentDateTime;

    private function SetCurrentTime(): void {
        $this->_CurrentDateTime = new \DateTime();
    }

    private function GetCurrentTime(): \DateTime {
        return $this->_CurrentDateTime;
    }

    /**
     * @return string Short time String, Format: H:i
     * @throws Exception
     */
    public function GetShortTimeString(): string {
        if($this->GetCurrentTime()) return $this->GetCurrentTime()->format("H:i");
        throw new Exception("Module not loaded yet.");
    }

    /**
     * @inheritDoc
     */
    function OnInitialize($args): void
    {
        $this->SetModuleName("Time");
    }

    /**
     * @inheritDoc
     */
    function OnIntervalUpdate($args): void
    {
        $this->SetCurrentTime();
        file_put_contents(__DIR__."/../Output/current_time.txt", $this->GetShortTimeString()." Uhr");
    }
}