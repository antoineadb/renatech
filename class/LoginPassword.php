<?php

class LoginPassword {

    private $_motpasse;
    private $_pseudo;

    public function __construct($motpasse, $pseudo) {
        $this->setMotpasse($motpasse);
        $this->setPseudo($pseudo);
    }

    public function setMotpasse($passe) {
        $this->_motpasse = $passe;
    }

    public function getMotpasse() {
        return $this->_motpasse;
    }

    public function setPseudo($pseudo) {
        $this->_pseudo = $pseudo;
    }

    public function getPseudo() {
        return $this->_pseudo;
    }
}
