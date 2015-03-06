<?php

class Login {

    private $_idlogin;
    private $_email;
    private $_motpasse;
    private $_pseudo;

    public function __construct($idlogin, $email, $motpasse, $pseudo) {
        $this->setIdlogin($idlogin);
        $this->setEmail($email);
        $this->setMotpasse($motpasse);
        $this->setPseudo($pseudo);
    }

    public function setIdlogin($id) {
        $this->_idlogin = (int) $id;
    }

    public function getIdlogin() {
        return $this->_idlogin;
    }

    public function setEmail($mail) {
        $this->_email = $mail;
    }

    public function getEmail() {
        return $this->_email;
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
