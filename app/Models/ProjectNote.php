<?php

namespace App\Models;

use App\Traits\HasUUID;
use App\View\Components\Markdown;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectNote extends Model
{
    use HasFactory, HasUUID;

    protected $fillable = [
        'project_id',
        'author_id',
        'parent_id',
        'title',
        'content',
        'pinned_at',
        'private_at',
        'mentions'
    ];

    protected $casts = [
        'pinned_at'  => 'datetime',
        'private_at' => 'datetime',
        'mentions'   => 'array',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function parent()
    {
        return $this->belongsTo(ProjectNote::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ProjectNote::class, 'parent_id')->orderBy('created_at');
    }

    // Scopes
    public function scopePinned($query)
    {
        return $query->whereNotNull('pinned_at');
    }

    public function scopeUnpinned($query)
    {
        return $query->whereNull('pinned_at');
    }

    public function scopeRootNotes($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('author_id', $userId)
              ->orWhereNull('private_at')
              ->orWhereJsonContains('mentions', $userId);
        });
    }

    // Move these into a policy...
    // Helper methods
    public function canBeViewedBy($userId)
    {
        return $this->author_id == $userId
            || !$this->private_at
            || (is_array($this->mentions) && in_array($userId, $this->mentions));
    }

    public function canBeEditedBy($userId)
    {
        return $this->author_id == $userId;
    }

    // end move into a policy

    public function getFormattedContentAttribute()
    {
        // Use your existing Markdown component
        $markdown = new Markdown();
        return $markdown->toHtml($this->content);
    }
}
