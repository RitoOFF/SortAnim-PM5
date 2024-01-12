<?php

namespace Rito\SortAnim\command;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\animation\TotemUseAnimation;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\world\sound\TotemUseSound;
use Rito\SortAnim\listener\EventListener;
use Rito\SortAnim\Main;

class SortCommand extends Command{
    public function __construct()
    {
        parent::__construct(Main::getInstance()->getConfig()->get("cmd-name"), Main::getInstance()->getConfig()->get("cmd-description"), "/". Main::getInstance()->getConfig()->get("cmd-name"));
        $this->setPermission("perm.sort");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("perm.sort")){
                EventListener::$sortActivated = !EventListener::$sortActivated;

                if (EventListener::$sortActivated) {
                    $sender->sendMessage(Main::getInstance()->getConfig()->get("message.active-menu"));
                } else {
                    $sender->sendMessage(Main::getInstance()->getConfig()->get("message.deactive-menu"));
                    
                }
            }else $sender->sendMessage(Main::getInstance()->getConfig()->get("message.no-perm"));
        }
    }
}