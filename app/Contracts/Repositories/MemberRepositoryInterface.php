<?php

namespace App\Contracts\Repositories;

use App\Models\Member;

interface MemberRepositoryInterface
{
    public function findById(int $id): ?Member;
    public function isActive(int $memberId): bool;
}