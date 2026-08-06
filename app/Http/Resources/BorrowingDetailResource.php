<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_pinjam' => $this->id_pinjam,
            'kode_transaksi' => $this->kode_transaksi,
            'anggota' => [
                'id_anggota' => $this->member?->id_anggota,
                'nama_siswa' => $this->member?->nama_siswa,
                'nisn' => $this->member?->nisn_siswa,
            ],
            'tgl_pinjam' => $this->tgl_pinjam?->format('Y-m-d'),
            'tenggat_kembali' => $this->tenggat_kembali?->format('Y-m-d'),
            'tgl_kembali_actual' => $this->tgl_kembali_actual?->format('Y-m-d'),
            'status_pinjam' => $this->status_pinjam,
            'denda' => $this->denda,
            'buku_dipinjam' => BorrowingDetailResource::collection($this->whenLoaded('books')),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}