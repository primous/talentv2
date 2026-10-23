<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token_hash',
        'expires_at',
        'last_used_at',
        'revoked_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /**
     * Génère un jeton permanent révocable (sans expiration automatique après 14 jours)
     * et enregistre son condensat (SHA-256)
     *
     * @return array{plain_token: string, record: self, url: string}
     */
    public static function generateForEmail(
        string $email,
        ?string $ip = null,
        ?string $userAgent = null,
        bool $revokePrevious = false
    ): array {
        $normalizedEmail = strtolower(trim($email));

        if ($revokePrevious) {
            self::revokeAllForEmail($normalizedEmail);
        }

        $plainToken = bin2hex(random_bytes(32)); // 64 caractères hexadécimaux cryptographiquement aléatoires
        $tokenHash = hash('sha256', $plainToken);

        $record = self::create([
            'email' => $normalizedEmail,
            'token_hash' => $tokenHash,
            'expires_at' => null, // Accès permanent (sans expiration temporelle automatique)
            'ip_address' => $ip,
            'user_agent' => $userAgent ? substr($userAgent, 0, 500) : null,
        ]);

        return [
            'plain_token' => $plainToken,
            'record' => $record,
            'url' => route('client.magic_access', ['token' => $plainToken]),
        ];
    }

    /**
     * Recherche un jeton valide à partir de sa chaîne brute
     * Accepte les jetons permanents (expires_at null) et rétrocompatibles non expirés
     */
    public static function findValidToken(string $plainToken): ?self
    {
        $plainToken = trim($plainToken);
        if (empty($plainToken)) {
            return null;
        }

        $tokenHash = hash('sha256', $plainToken);

        return self::where('token_hash', $tokenHash)
            ->whereNull('revoked_at')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();
    }

    /**
     * Vérifie si le jeton est encore valide (non révoqué et non expiré si date présente)
     */
    public function isValid(): bool
    {
        if ($this->revoked_at !== null) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Marque le jeton comme utilisé lors d'une session de connexion
     */
    public function markAsUsed(): void
    {
        $this->last_used_at = now();
        $this->save();
    }

    /**
     * Révoque ce jeton spécifique
     */
    public function revoke(): void
    {
        $this->revoked_at = now();
        $this->save();
    }

    /**
     * Révoque tous les jetons actifs associés à une adresse e-mail
     */
    public static function revokeAllForEmail(string $email): int
    {
        $normalizedEmail = strtolower(trim($email));

        return self::where('email', $normalizedEmail)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }
}
