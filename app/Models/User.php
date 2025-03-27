<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\User\HasQueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasQueryScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public static $sortable = [
        'name',
        'email',
    ];

    /**
     * Create a new personal access token for the user with tenant context.
     */
    public function createTokenWithTenant(string $name, string $tenantId, array $abilities = ['*'])
    {
        $token = $this->createToken($name, $abilities);

        // Store the tenant ID in the token's metadata
        $token->accessToken->forceFill([
            'metadata' => json_encode(['tenant_id' => $tenantId])
        ])->save();

        return $token;
    }
}
