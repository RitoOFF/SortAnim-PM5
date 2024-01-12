<?php

namespace Rito\SortAnim\listener;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\animation\TotemUseAnimation;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\world\sound\TotemUseSound;

class EventListener implements Listener{
    public static $sortActivated = false;
    private $cooldownForce;
    private $cooldownInv;

    private $cooldownProp;
    private $cooldownSpeed;

    public function onAnim(PlayerToggleSneakEvent $event){
        $player = $event->getPlayer();
       if (self::$sortActivated) {
           $menu = InvMenu::create(InvMenu::TYPE_HOPPER);
           $inv = $menu->getInventory();
           $enchant = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 10);
           if (isset($this->cooldownProp[$player->getName()]) && $this->cooldownProp[$player->getName()] > time()) {
               $inv->addItem(VanillaItems::PAPER()->setCustomName("§cPropulsion")->setLore(["BLOQUER"]));
           } else {
               $inv->addItem(VanillaItems::PAPER()->addEnchantment($enchant)->setCustomName("§r§aPropulsion")->setLore(["§eClick to active sort !"]));
           }

           if (isset($this->cooldownInv[$player->getName()]) && $this->cooldownInv[$player->getName()] > time()) {
               $inv->addItem(VanillaItems::PAPER()->setCustomName("§9Invisibilité")->setLore(["§4BLOQUER"]));
           } else {
               $inv->addItem(VanillaItems::PAPER()->addEnchantment($enchant)->setCustomName("§r§9Invisibilité")->setLore(["§eClick to active sort !"]));
           }
           if (isset($this->cooldownForce[$player->getName()]) && $this->cooldownForce[$player->getName()] > time()) {
               $inv->addItem(VanillaItems::PAPER()->setCustomName("§5Force")->setLore(["§4BLOQUER"]));
           } else {
               $inv->addItem(VanillaItems::PAPER()->addEnchantment($enchant)->setCustomName("§r§5Force")->setLore(["§eClick to active sort !"]));
           }
           if (isset($this->cooldownSpeed[$player->getName()]) && $this->cooldownSpeed[$player->getName()] > time()) {
               $inv->addItem(VanillaItems::PAPER()->setCustomName("§6Vitesse")->setLore(["§4BLOQUER"]));
           } else {
               $inv->addItem(VanillaItems::PAPER()->addEnchantment($enchant)->setCustomName("§r§6Vitesse")->setLore(["§eClick to active sort !"]));
           }
           $inv->addItem(VanillaBlocks::BARRIER()->asItem()->setCustomName("§r§4Quittez")->setLore(["§eClick pour quittez le menu !"]));

           $menu->setListener(function (InvMenuTransaction $transaction) use ($menu): InvMenuTransactionResult {
               $item = $transaction->getItemClicked();
               return $transaction->discard()->then(function (Player $player) use ($item, $menu) {
                   if ($item->getName() === "§r§aPropulsion") {
                       $player->removeCurrentWindow();
                       $player->broadcastAnimation(new TotemUseAnimation($player));
                       $player->broadcastSound(new TotemUseSound());
                       $player->setMotion($player->getDirectionVector()->add(0, 5, 0));
                       $cooldownProp = 120;
                       $this->cooldownProp[$player->getName()] = time() + $cooldownProp;
                   }
                   if ($item->getName() === "§r§9Invisibilité") {
                       $player->removeCurrentWindow();
                       $player->broadcastAnimation(new TotemUseAnimation($player));
                       $player->broadcastSound(new TotemUseSound());
                       $player->getEffects()->add(new EffectInstance(VanillaEffects::INVISIBILITY(), 200, 100, false));
                       $cooldownInv = 120;
                       $this->cooldownInv[$player->getName()] = time() + $cooldownInv;
                   }
                   if ($item->getName() === "§r§5Force") {
                       $player->removeCurrentWindow();
                       $player->broadcastAnimation(new TotemUseAnimation($player));
                       $player->broadcastSound(new TotemUseSound());
                       $player->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 200, 2, false));
                       $cooldownForce = 120;
                       $this->cooldownForce[$player->getName()] = time() + $cooldownForce;
                   }
                   if ($item->getName() === "§r§6Vitesse") {
                       $player->removeCurrentWindow();
                       $player->broadcastAnimation(new TotemUseAnimation($player));
                       $player->broadcastSound(new TotemUseSound());
                       $player->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 200, 3, false));
                       $cooldownSpeed = 120;
                       $this->cooldownSpeed[$player->getName()] = time() + $cooldownSpeed;
                   }
                   if ($item->getName() === "§r§4Quittez") {
                       $player->removeCurrentWindow();
                   }
               });
           });

           $menu->setName("Sort");
           $menu->send($player);
       }
    }
}