<?php

class ConfigAccueilDefault {

    private $_id;
    private $_id_utilisateur;
    private $_idcentrale;

    function __construct($_id_utilisateur, $_idcentrale) {
        $this->set_id_utilisateur($_id_utilisateur);
        $this->set_idcentrale($_idcentrale);
    }

    function get_id_utilisateur() {
        return $this->_id_utilisateur;
    }

    function get_idcentrale() {
        return $this->_idcentrale;
    }

    function set_id_utilisateur($_id_utilisateur) {
        $this->_id_utilisateur = $_id_utilisateur;
    }

    function set_idcentrale($_idcentrale) {
        $this->_idcentrale = $_idcentrale;
    }

    function get_id() {
        return $this->_id;
    }

}
