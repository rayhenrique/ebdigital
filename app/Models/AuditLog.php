<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string $auditable_type
 * @property int $auditable_id
 * @property array|null $payload_before
 * @property array|null $payload_after
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read User|null $user
 */
class AuditLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'congregation_id',
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'payload_before',
        'payload_after',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload_before' => 'array',
            'payload_after' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * The user who performed this audited action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Congregação vinculada ao log de auditoria.
     */
    public function congregation(): BelongsTo
    {
        return $this->belongsTo(Congregation::class, 'congregation_id');
    }
}
