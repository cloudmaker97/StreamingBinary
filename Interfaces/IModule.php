<?php
namespace Interfaces;

/**
 * Interface IModule
 * @package Interfaces
 */
interface IModule {
    public function GetModuleName() : string;
    public function SetModuleName(string $Name) : void;
    public function SetSettings(array $Settings) : void;
    public function GetSettings() : array;
}