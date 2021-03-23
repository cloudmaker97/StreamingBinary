<?php
namespace Modules\Time;
use Classes\Module;
use Exception;
use Helper\Output\OutputMessage;

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
        $this->SetOutputFolder("Time");
    }

    /**
     * @inheritDoc
     */
    function OnIntervalUpdate($args): void
    {
        $this->SetCurrentTime();
        $this->WriteFile("current_time.txt", $this->GetShortTimeString()." Uhr");
        OutputMessage::create("Die Zeit-Informationen wurden abgerufen", "Modules");
    }
}