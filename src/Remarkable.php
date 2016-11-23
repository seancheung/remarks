<?php

namespace Panoscape\Remarks;

/*
 * A model which can be remarked
 */
trait Remarkable
{
    /**
     * Boot the Remarkable trait for a model.
     *
     * @return void
     */
    public static function bootRemarkable()
    {
        static::deleting(function($model) {
            if($model->forceDelete()) {
                $model->clearRemarks();
            }            
        });
    }

    /**
     * Collection of remarks on this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function remarks()
    {
        return $this->morphMany(Remark::class, 'model');
    }

    /**
     * Collection of likes on this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->remarks()->where('type', Remark::LIKE);
    }

    /**
     * Collection of dislikes on this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function dislikes()
    {
        return $this->remarks()->where('type', Remark::DISLIKE);
    }

    /**
     * Add a like for model by the given user.
     *
     * @param mixed $user If null will use currently logged in user.
     * @return void
     */
    public function like($user = null)
    {
        app(Remarks::class)->add($this, Remark::LIKE, $user);
    }

    /**
     * Remove a like from this record for the given user.
     *
     * @param mixed $user If null will use currently logged in user.
     * @return void
     */
    public function unlike($user = null)
    {
        app(Remarks::class)->remove($this, Remark::LIKE, $user);
    }

    /**
     * Toggle like for model by the given user.
     *
     * @param mixed $user If null will use currently logged in user.
     * @return void
     */
    public function toggleLike($user = null)
    {
        app(Remarks::class)->toggle($this, Remark::LIKE, $user);
    }

    /**
     * Has the user already remarked remarkable model.
     *
     * @param mixed $user
     * @return bool
     */
    public function remarked($user = null)
    {
        return app(Remarks::class)->has($this, Remark::ALL, $user);
    }

    /**
     * Has the user already liked remarkable model.
     *
     * @param mixed $user
     * @return bool
     */
    public function liked($user = null)
    {
        return app(Remarks::class)->has($this, Remark::LIKE, $user);
    }

    /**
     * Delete remarks related to the current record.
     *
     * @return void
     */
    public function clearRemarks()
    {
        app(Remarks::class)->clearByModel($this, Remark::ALL);
    }

    /**
     * Delete likes related to the current record.
     *
     * @return void
     */
    public function clearLikes()
    {
        app(Remarks::class)->clearByModel($this, Remark::LIKE);
    }

    /**
     * Add a dislike for model by the given user.
     *
     * @param mixed $user If null will use currently logged in user.
     * @return void
     */
    public function dislike($user = null)
    {
        app(Remarks::class)->add($this, Remark::DISLIKE, $user);
    }

    /**
     * Remove a dislike from this record for the given user.
     *
     * @param mixed $user If null will use currently logged in user.
     * @return void
     */
    public function undislike($user = null)
    {
        app(Remarks::class)->remove($this, Remark::DISLIKE, $user);
    }

    /**
     * Toggle dislike for model by the given user.
     *
     * @param mixed $user If null will use currently logged in user.
     * @return void
     */
    public function toggleDislike($user = null)
    {
        app(Remarks::class)->toggle($this, Remark::DISLIKE, $user);
    }

    /**
     * Has the user already disliked remarkable model.
     *
     * @param mixed $user
     * @return bool
     */
    public function disliked($user = null)
    {
        return app(Remarks::class)->has($this, Remark::DISLIKE, $user);
    }

    /**
     * Delete dislikes related to the current record.
     *
     * @return void
     */
    public function clearDislikes()
    {
        app(Remarks::class)->clearByModel($this, Remark::DISLIKE);
    }

    /**
     * Did the currently logged in user remarked this model.
     *
     * @return bool
     */
    public function getRemarkedAttribute()
    {
        return $this->remarked();
    }

    /**
     * Did the currently logged in user like this model.
     *
     * @return bool
     */
    public function getLikedAttribute()
    {
        return $this->liked();
    }

    /**
     * Did the currently logged in user dislike this model.
     *
     * @return bool
     */
    public function getDislikedAttribute()
    {
        return $this->disliked();
    }

    /**
     * Model remarksCount attribute.
     *
     * @return int
     */
    public function getRemarksCountAttribute()
    {
        return $this->remarks()->count();
    }

    /**
     * Model likesCount attribute.
     *
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Model dislikesCount attribute.
     *
     * @return int
     */
    public function getDislikesCountAttribute()
    {
        return $this->dislikes()->count();
    }

    /**
     * Fetch records that are remarked by a given user.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed $user
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWhereRemarkedBy($query, $user = null)
    {
        return app(Remarks::class)->scopeWhereRemarkedBy($query, Remark::ALL, $user);
    }

    /**
     * Fetch records that are liked by a given user.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed $user
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWhereLikedBy($query, $user = null)
    {
        return app(Remarks::class)->scopeWhereRemarkedBy($query, Remark::LIKE, $user);
    }

    /**
     * Fetch records that are disliked by a given user.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param mixed $user
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWhereDislikedBy($query, $user = null)
    {
        return app(Remarks::class)->scopeWhereRemarkedBy($query, Remark::DISLIKE, $user);
    }
}