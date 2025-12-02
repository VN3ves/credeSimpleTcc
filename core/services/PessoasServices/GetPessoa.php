<?php

class GetPessoa
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPessoa($id = null)
    {
        if (empty($id)) {
            return;
        }

        $id = (int) $id;

        $query = $this->db->query('SELECT * FROM `tblPessoa` WHERE `id` = ?', array($id));

        if (!$query) {
            return false;
        }

        return $query->fetch();
    }
}
