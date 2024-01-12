<?php

namespace Rito\SortAnim;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use Rito\SortAnim\command\SortCommand;
use Rito\SortAnim\listener\EventListener;

class Main extends PluginBase{
    public static Config $config;

    use SingletonTrait;

    protected function onLoad(): void {
        self::setInstance($this);
    }


    public function onEnable(): void
    {
        $this->getResource("config.yml");
        $this->saveDefaultConfig();
        $this->getLogger()->notice("Enable ->  Plugin Spike BY RITO | disocrd: rito.off");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("", new SortCommand());

        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }}
    public function onDisable(): void
    {
        $this->getLogger()->notice("Disable ->  Plugin Spike RITO | disocrd: rito.off");
    }
}