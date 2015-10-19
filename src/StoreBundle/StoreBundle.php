<?php

namespace StoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class StoreBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
