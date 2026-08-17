<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

describe('User model', function () {
    it('casts email_verified_at to datetime', function () {
        $user = User::factory()->make(['email_verified_at' => '2026-08-17 10:00:00']);

        expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);
    });

    it('hashes password on assignment', function () {
        $user = User::factory()->make(['password' => 'plain-secret']);

        expect($user->password)->not->toBe('plain-secret')
            ->and(Hash::check('plain-secret', $user->password))->toBeTrue();
    });

    it('hides password and remember_token from array output', function () {
        $user = User::factory()->make()->setRelation('none', null);

        $exposed = $user->toArray();

        expect($exposed)->not->toHaveKey('password')
            ->and($exposed)->not->toHaveKey('remember_token')
            ->and($exposed)->toHaveKeys(['name', 'email']);
    });
});
