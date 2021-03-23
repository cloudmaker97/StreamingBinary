<?php
namespace Interfaces;

/**
 * Interface IModule
 * @package Interfaces
 */
interface IModule {
    public function GetModuleName() : string;
    public function SetModuleName(string $Name) : void;
}