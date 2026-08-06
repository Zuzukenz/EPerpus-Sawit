<?php

namespace App\Repositories;

use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Models\Member;

class MemberRepository implements MemberRepositoryInterface
{
    public function findById(int $id): ?Member
    {
        return Member::find($id);
    }

    public function isActive(int $memberId): bool
    {
        $member = Member::where('id_anggota', $memberId)->first();
        return $member && $member->status === true;
    }
}