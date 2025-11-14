<?php

function processarJobs()
{
    $pythonBin = "/var/www/venv/bin/python3";
    $scriptPath = "/var/www/server/webservices/controlid/processarJobsSync.py";

    // Checa se os dois arquivos existem
    if (!file_exists($pythonBin) || !file_exists($scriptPath)) {
        Log::error("Arquivos de Python não encontrados");
        return false;
    }

    $limit = 30;

    // Monta o comando
    $command = sprintf(
        '%s %s --limit %d 2>&1',
        escapeshellcmd($pythonBin),
        escapeshellarg($scriptPath),
        intval($limit)
    );

    // Executa o comando
    exec($command, $output, $returnCode);

    // Pega a última linha (JSON de resultado)
    $lastLine = trim(end($output));

    // Tenta decodificar o JSON
    $resultado = json_decode($lastLine, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        Log::info("Jobs processados: " . $resultado['jobs_processados']);
        Log::info("Sucessos: " . $resultado['sucessos']);
        Log::info("Falhas: " . $resultado['falhas']);
        Log::info("Mensagem: " . $resultado['mensagem']);
        Log::info("Duração: " . $resultado['duracao_segundos']);
        Log::info("Return code: " . $resultado['return_code']);
        return true;
    } else {
        Log::error("Erro ao decodificar resposta: " . json_last_error_msg());
        Log::error("Output: " . implode("\n", $output));
        Log::error("Return code: " . $returnCode);
        return false;
    }
    return false;
}
