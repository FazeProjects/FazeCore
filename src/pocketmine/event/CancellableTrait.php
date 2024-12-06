<?php

namespace pocketmine\event;

trait CancellableTrait{
    /** @var bool */
    private $isCancelled = false;

    public function isCancelled() : bool{
        return $this->isCancelled;
    }

    public function cancel() : void{
        $this->isCancelled = true;
    }

    public function uncancel() : void{
        $this->isCancelled = false;
    }
}