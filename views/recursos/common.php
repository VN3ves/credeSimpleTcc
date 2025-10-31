<?php
function formatarStatus($status) {
    $statusMap = [
        'T' => '<span class="badge badge-success">Ativo</span>',
        'F' => '<span class="badge badge-danger">Inativo</span>',
        'B' => '<span class="badge badge-warning">Bloqueado</span>',
    ];

    return $statusMap[$status] ?? '<span class="badge badge-secondary">Desconhecido</span>';
}

function formatarCondicao($condicao) {
    $condicaoMap = [
        'OFF' => '<span class="badge badge-success">Offline</span>',
        'ON' => '<span class="badge badge-warning">Online</span>',
        'ERRO' => '<span class="badge badge-danger">Erro</span>',
    ];
    return $condicaoMap[$condicao] ?? '<span class="badge badge-secondary">Desconhecido</span>';
}