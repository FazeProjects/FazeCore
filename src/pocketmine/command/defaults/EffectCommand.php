<?php



namespace pocketmine\command\defaults;


use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\InstantEffect;
use pocketmine\event\TranslationContainer;
use pocketmine\utils\TextFormat;

class EffectCommand extends VanillaCommand {

	/**
	 * EffectCommand constructor.
	 *
	 * @param $name
	 */
	public function __construct($name){
		parent::__construct(
			$name,
			"%pocketmine.command.effect.description",
			"/effect <player> <effect> [number of seconds] [level] [removeParticles] OR /effect <player> clear"
		);
		$this->setPermission("pocketmine.command.effect;pocketmine.command.effect.other");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $currentAlias
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 2){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return true;
		}

		$player = $sender->getServer()->getPlayer($args[0]);

		if($player === null){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
			return true;
		}

		if($player->getName() != $sender->getName() && !$sender->hasPermission("pocketmine.command.effect.other")){
			$sender->sendMessage("You don't have permission to give effect to other player .");
			return true;
		}

		if(strtolower($args[1]) === "clear"){
			foreach($player->getEffects() as $effect){
				$player->removeEffect($effect->getId());
			}

			$sender->sendMessage(new TranslationContainer("commands.effect.success.removed.all", [$player->getDisplayName()]));
			return true;
		}

		$effect = Effect::getEffectByName($args[1]);

		if($effect === null){
			$effect = Effect::getEffect((int) $args[1]);
		}

		if($effect === null){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.effect.notFound", [(string) $args[1]]));
			return true;
		}

		$duration = 300;
		$amplification = 0;

		if(count($args) >= 3){
			$duration = (int) $args[2];
			if(!($effect instanceof InstantEffect)){
				$duration *= 20;
			}
		}elseif($effect instanceof InstantEffect){
			$duration = 1;
		}

		if(count($args) >= 4){
			$amplification = (int) $args[3];
			if($amplification > 255){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.num.tooBig", [(string) $args[3], "255"]));
				return true;
			}elseif($amplification < 0){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.num.tooSmall", [(string) $args[3], "0"]));
				return true;
			}
		}

		if(count($args) >= 5){
			$v = strtolower($args[4]);
			if($v === "on" or $v === "true" or $v === "t" or $v === "1"){
				$effect->setVisible(false);
			}
		}

		if($duration === 0){
			if(!$player->hasEffect($effect->getId())){
				if(count($player->getEffects()) === 0){
					$sender->sendMessage(new TranslationContainer("commands.effect.failure.notActive.all", [$player->getDisplayName()]));
				}else{
					$sender->sendMessage(new TranslationContainer("commands.effect.failure.notActive", [$effect->getName(), $player->getDisplayName()]));
				}
				return true;
			}

			if($player->removeEffect($effect->getId())){
				$sender->sendMessage(new TranslationContainer("commands.effect.success.removed", [$effect->getName(), $player->getDisplayName()]));
			}
		}else{
			$effect->setDuration($duration)->setAmplifier($amplification);

			if($player->addEffect($effect)){
				self::broadcastCommandMessage($sender, new TranslationContainer("%commands.effect.success", [$effect->getName(), $effect->getId(), $effect->getAmplifier(), $player->getDisplayName(), $effect->getDuration() / 20]));
			}
		}


		return true;
	}
}
