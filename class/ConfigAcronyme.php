<?php

class ConfigAcronyme {

    private $_id_acronyme;
    private $_id_centrale;
    private $_ref_interne;
    private $_num_projet;

    function __construct($_id_centrale, $_ref_interne, $_num_projet) {
        $this->set_id_centrale($_id_centrale);
        $this->set_ref_interne($_ref_interne);
        $this->set_num_projet($_num_projet);
    }

    function get_id_centrale() {
        return $this->_id_centrale;
    }

    function get_ref_interne() {
        return $this->_ref_interne;
    }

    function get_num_projet() {
        return $this->_num_projet;
    }

    function set_id_centrale($_id_centrale) {
        $this->_id_centrale = $_id_centrale;
    }

    function set_ref_interne($_ref_interne) {
        $this->_ref_interne = $_ref_interne;
    }

    function set_num_projet($_num_projet) {
        $this->_num_projet = $_num_projet;
    }

    function get_id_acronyme() {
        return $this->_id_acronyme;
    }

}
