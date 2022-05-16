<?php

namespace Dotlogics\Grapesjs\App\Editor;

abstract class Base
{
    public function toJson()
    {
        return json_encode($this);
    }
    
    public function __toString()
    {
        return $this->toJson();
    }

    public function toArray()
    {
    	return json_decode($this->toJson(), true);
    }
}
