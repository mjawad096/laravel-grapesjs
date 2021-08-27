<?php

namespace Dotlogics\Grapesjs\App\Editor;

class EditorBaseClass
{
    public function __toString()
    {
        return $this->toJson();
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public function toArray(){
    	return json_decode($this->toJson(), true);
    }
}
