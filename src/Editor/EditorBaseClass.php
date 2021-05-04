<?php

namespace Topdot\Grapesjs\Editor;

class EditorBaseClass
{
    public function __toString()
    {
        return json_encode($this);
    }
}
