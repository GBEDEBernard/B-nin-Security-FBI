<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            $model->logActivity('created');
        });

        static::updated(function (Model $model) {
            $model->logActivity('updated');
        });

        static::deleted(function (Model $model) {
            $model->logActivity('deleted');
        });
    }

    protected function logActivity(string $event): void
    {
        ActivityLog::create([
            'description' => $this->getActivityDescription($event),
            'subject_type' => static::class,
            'subject_id' => $this->getKey(),
            'causer_id' => $this->getCauserId(),
            'causer_type' => $this->getCauserType(),
            'properties' => $this->getActivityProperties($event),
            'ip_address' => $this->getIpAddress(),
        ]);
    }

    protected function getActivityDescription(string $event): string
    {
        $name = method_exists($this, 'getActivityName')
            ? $this->getActivityName()
            : $this->{$this->getActivityIdentifier()} ?? static::class;

        return match ($event) {
            'created' => "Création de {$name}",
            'updated' => "Modification de {$name}",
            'deleted' => "Suppression de {$name}",
            default => "{$event} sur {$name}",
        };
    }

    protected function getActivityIdentifier(): string
    {
        return property_exists($this, 'activityIdentifier') ? $this->activityIdentifier : 'id';
    }

    protected function getActivityProperties(string $event): array
    {
        if ($event === 'deleted') {
            return ['attributes' => $this->getOriginal()];
        }

        return [
            'attributes' => $this->getDirty(),
            'original' => $this->getOriginal(),
            'changes' => $this->getChanges(),
        ];
    }

    protected function getCauserId(): ?int
    {
        if (auth()->check()) {
            return auth()->id();
        }
        return null;
    }

    protected function getCauserType(): ?string
    {
        if (auth()->check()) {
            return get_class(auth()->user());
        }
        return null;
    }

    protected function getIpAddress(): ?string
    {
        $request = request();

        if (! $request) {
            return null;
        }

        return $request->ip() ?: null;
    }
}
