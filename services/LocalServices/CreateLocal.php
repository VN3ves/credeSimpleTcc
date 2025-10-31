<?php

class CreateLocal extends MainModel
{

    public function createLocal($nomeOficial, $cep, $logradouro, $numero, $bairro, $cidade, $estado, $complemento, $latitude, $longitude)
    {
        if (empty($nomeOficial)) {
            return false;
        }

        $query = $this->db->insert('tblEventoLocal', array(
            'nomeOficial' => $nomeOficial,
            'cep' => $cep,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'complemento' => $complemento,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status' => 'T'
        ));

        Log::info($this->db->error());

        if (!$query) {
            return false;
        }

        return $query;
    }
} 