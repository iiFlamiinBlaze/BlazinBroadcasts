<?php
/**
 *  ____  _            _______ _          _____
 * |  _ \| |          |__   __| |        |  __ \
 * | |_) | | __ _ _______| |  | |__   ___| |  | | _____   __
 * |  _ <| |/ _` |_  / _ \ |  | '_ \ / _ \ |  | |/ _ \ \ / /
 * | |_) | | (_| |/ /  __/ |  | | | |  __/ |__| |  __/\ V /
 * |____/|_|\__,_/___\___|_|  |_| |_|\___|_____/ \___| \_/
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze for the PocketMine-MP Community!
 *
 * @author iiFlamiinBlaze
 * Twitter: https://twitter.com/iiFlamiinBlaze
 * GitHub: https://github.com/iiFlamiinBlaze
 * Discord: https://discord.gg/znEsFsG
 */
declare(strict_types=1);

namespace iiFlamiinBlaze\BlazinBroadcasts;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\Task;

class CommandTask extends Task{

	public function onRun(int $tick) : void{
		foreach(BlazinBroadcasts::getInstance()->getConfig()->get("commands") as $command){
			$command = str_replace(array(
				"&",
				"{line}",
				"{max_players}",
				"{online_players}",
				"{tps}",
				"{motd}"
			), array(
				"§",
				"\n",
				BlazinBroadcasts::getInstance()->getServer()->getMaxPlayers(),
				count(BlazinBroadcasts::getInstance()->getServer()->getOnlinePlayers()),
				BlazinBroadcasts::getInstance()->getServer()->getTicksPerSecond(),
				BlazinBroadcasts::getInstance()->getServer()->getMotd()
			), $command);
			BlazinBroadcasts::getInstance()->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
		}
	}
}