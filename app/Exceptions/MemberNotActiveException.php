<?php

namespace App\Exceptions;

use Exception;

class MemberNotActiveException extends Exception
{
    public function __construct()
    {
        parent::__construct('Anggota tidak aktif atau tidak ditemukan. Transaksi tidak dapat diproses.');
    }
}