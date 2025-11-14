<?php

function processarJobs($pdo, $idPessoa, $idEvento, $idArquivo = null)
{
    $pythonBin = "/var/www/venv/bin/python3";
    $scriptPath = "/var/www/server/webservices/controlid/processarJobsSync.py";

    // Checa se os dois arquivos existem
    if (!file_exists($pythonBin) || !file_exists($scriptPath)) {
        Log::error("Arquivos de Python não encontrados");
        return false;
    }

    if($idArquivo == null) {
        $sql = "SELECT id FROM tblArquivo WHERE idReferencia = :idPessoa AND tipoReferencia = 'PESSOA' AND tipoArquivo = 'AVATAR' ORDER BY dataCadastro DESC LIMIT 1";
        $stmt = $pdo->query($sql, array(
            'idPessoa' => $idPessoa
        ));
        $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);
        $idArquivo = $arquivo['id'];
    }

    if($idPessoa == null || $idEvento == null) {
        Log::error("ID da pessoa, ID do arquivo ou ID do evento não fornecidos");
        return false;
    }

    // Cria o job
    $sql = "INSERT INTO tblJobSync (idPessoa, idArquivo, idEvento) VALUES (:idPessoa, :idArquivo, :idEvento)";
    $stmt = $pdo->query($sql, array(
        'idPessoa' => $idPessoa,
        'idArquivo' => $idArquivo,
        'idEvento' => $idEvento
    ));
    $idJob = $pdo->lastInsertId();


    // Monta o comando para executar em background
    $command = sprintf(
        '%s %s --job-id %d > /dev/null 2>&1 &',
        escapeshellcmd($pythonBin),
        escapeshellarg($scriptPath),
        intval($idJob)
    );

    // Executa o comando em background (não espera o resultado)
    exec($command);

    // Retorna sucesso imediatamente, pois o job foi criado e o processamento iniciado
    Log::info("Job de sincronização criado com ID: {$idJob} para pessoa {$idPessoa}");
    return true;
}
