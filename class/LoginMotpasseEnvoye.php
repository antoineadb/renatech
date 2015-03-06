<?php

class LoginMotpasseEnvoye {

    private $_motpasseenvoye;
    private $_pseudo;

    public function __construct($motpasseenvoyee, $pseudo) {
        $this->setMotpasseenvoye($motpasseenvoyee);
        $this->setPseudo($pseudo);
    }

    public function setMotpasseenvoye($passe) {
        $this->_motpasseenvoye = $passe;
    }

    public function getMotpasseenvoye() {
        return $this->_motpasseenvoye;
    }

    public function setPseudo($pseudo) {
        $this->_pseudo = $pseudo;
    }

    public function getPseudo() {
        return $this->_pseudo;
    }

}

