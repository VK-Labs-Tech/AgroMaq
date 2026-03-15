<?php

namespace App\Enums;

enum DiarioBordoStatus: string
{
    case PreViagem = 'pre_viagem';
    case Checklist = 'checklist';
    case EmTransito = 'em_transito';
    case Encerrado = 'encerrado';
}

