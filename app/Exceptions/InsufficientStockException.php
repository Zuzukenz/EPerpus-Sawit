<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function __construct(string $bookTitle, int $requested, int $available)
    {
        parent::__construct(
            "Stok tidak mencukupi untuk buku '{$bookTitle}'. " .
            "Diminta: {$requested}, Tersedia: {$available}"
        );
    }
}