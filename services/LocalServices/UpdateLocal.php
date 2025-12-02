<?php

class UpdateLocal extends MainModel
{

    public function updateLocal($id, $nomeOficial, $cep, $logradouro, $numero, $bairro, $cidade, $estado, $complemento, $latitude, $longitude)
    {
        if (empty($id) || empty($nomeOficial)) {
            return false;
        }

        $query = $this->db->update('tblEventoLocal', 'id', $id, array(
            'nomeOficial' => $nomeOficial,
            'cep' => $cep,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'complemento' => $complemento,
            'latitude' => $latitude,
            'longitude' => $longitude
        ));

        if (!$query) {
            return false;
        }

        return $query;
    }
}
