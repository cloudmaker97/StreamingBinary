<?php
namespace Helper\Output;

use DateTime;

/**
 * Class OutputLogger
 * @package Helper
 */
class OutputMessage
{
    const OUTPUT_CATEGORY_GENERAL = "General";

    private DateTime $_MessageTime;
    private string $_MessageCategory;
    private string $_MessageContent;

    public function __construct()
    {
        $this->_MessageTime = new DateTime();
        $this->SetCategory("General");
        $this->SetMessage("Kein Inhalt angegeben");
    }

    /**
     * Set the category for the message
     * @param string $Category
     */
    private function SetCategory(string $Category) {
        $this->_MessageCategory = $Category;
    }

    /**
     * Get the category for the message
     * @return string Message Category
     */
    private function GetCategory(): string {
        return $this->_MessageCategory;
    }

    /**
     * Set the message content
     * @param string $Message
     */
    private function SetMessage(string $Message) {
        $this->_MessageContent = $Message;
    }

    /**
     * Get the message content
     * @return string Message
     */
    private function GetMessage(): string {
        return $this->_MessageContent;
    }

    /**
     * Set message DateTime
     * @param DateTime $dateTime
     */
    private function SetDateTime(DateTime $dateTime) {
        $this->_MessageTime = $dateTime;
    }

    /**
     * Get message DateTime
     * @return DateTime
     */
    private function GetDateTime(): DateTime {
        return $this->_MessageTime;
    }

    /**
     * Writes a message into console
     */
    protected function WriteLine() {
        echo sprintf("[%s] %s - %s\r\n", $this->GetDateTime()->format("H:i:s"), $this->GetCategory(), $this->GetMessage());
    }

    /**
     * Writes a debug message into console
     * @param $mixed
     */
    protected function WriteDebug($mixed) {
        echo "\r\n\t>>>>> Start of Debug-Message <<<<\r\n";
        print_r($mixed);
        echo "\t>>>>> End of Debug-Message <<<<\r\n";
    }

    /**
     * Creates a message object
     * @param string $Message
     * @param string $Category
     * @return OutputMessage
     */
    static function create(string $Message, string $Category = self::OUTPUT_CATEGORY_GENERAL): OutputMessage
    {
        $Logger = new OutputMessage();
        $Logger->SetMessage($Message);
        $Logger->SetCategory($Category);
        $Logger->WriteLine();
        return $Logger;
    }

    /**
     * Creates a message object with debug output
     * @param $DebugData
     * @return OutputMessage
     */
    static function createDebug($DebugData): OutputMessage
    {
        $Logger = new OutputMessage();
        $Logger->WriteDebug($DebugData);
        return $Logger;
    }


}