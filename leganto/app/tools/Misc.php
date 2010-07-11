<?php

class Misc {

    public function isAllowedToControl($user) {
        return Environment::getUser()->isAuthenticated() &&
               (System::user()->getId() == $user || System::user()->role != 'common');
    }

}

?>
