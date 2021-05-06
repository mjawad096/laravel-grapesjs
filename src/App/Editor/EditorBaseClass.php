<?php

namespace Topdot\Grapesjs\App\Editor;

class EditorBaseClass
{
    public function __toString()
    {
        return json_encode($this);
    }
}
