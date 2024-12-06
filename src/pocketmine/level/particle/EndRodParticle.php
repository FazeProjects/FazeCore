<?php

/* 

  @author vk.com/sanekmelkov and vk.com/kirillmineproyt

	█   ▀ ▀█▀ █▀▀ █▀▀ █▀█ █▀█ █▀▀   ▄▄   █ █ ▄▀▄ █▄ █ ▀ █   █   ▄▀▄
	█▄▄ █  █  ██▄ █▄▄ █▄█ █▀▄ ██▄        ▀▄▀ █▀█ █ ▀█ █ █▄▄ █▄▄ █▀█
 
 
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
 
  @author vk.com/sanekmelkov and vk.com/kirillmineproyt
 
*/

namespace pocketmine\level\particle;

use pocketmine\math\Vector3;

class EndRodParticle extends GenericParticle {
	/**
	 * ExplodeParticle constructor.
	 *
	 * @param Vector3 $pos
	 */
	public function __construct(Vector3 $pos){
		parent::__construct($pos, Particle::TYPE_END_ROD);
	}
}
