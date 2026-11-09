<?php

namespace App\Services\Users;

use App\Models\User;

class DuplicateAccountGuard
{
    public function exists(string $firstName, string $lastName, string $dateOfBirth, string $state): bool
    {
        return User::query()
            ->whereDate('date_of_birth', $dateOfBirth)
            ->where('state', $state)
            ->get(['first_name', 'last_name'])
            ->contains(fn (User $user): bool => $this->namesMatch($firstName, $user->first_name)
                && $this->namesMatch($lastName, $user->last_name));
    }

    public function namesMatch(string $left, string $right): bool
    {
        $left = $this->normalize($left);
        $right = $this->normalize($right);

        if ($left === '' || $right === '') {
            return false;
        }

        if ($left === $right || (metaphone($left) !== '' && metaphone($left) === metaphone($right))) {
            return true;
        }

        if (strlen($left) > 255 || strlen($right) > 255) {
            return false;
        }

        $limit = max(strlen($left), strlen($right)) <= 5 ? 1 : 2;

        return levenshtein($left, $right) <= $limit;
    }

    private function normalize(string $name): string
    {
        return preg_replace('/[^a-z]/', '', mb_strtolower($name)) ?? '';
    }
}
