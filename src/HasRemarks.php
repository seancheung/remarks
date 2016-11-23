<?php

namespace Panoscape\Remarks;

/*
 * A user who can make remarks
 */
trait HasRemarks
{
    /**
     * Boot the HasRemarks trait for a model.
     *
     * @return void
     */
    public static function bootHasRemarks()
    {
        static::deleting(function($model) {
            if($model->forceDelete()) {
                $model->clearRemarks();
            }            
        });
    }

    /**
     * Collection of remarks of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function remarks()
    {
        return $this->morphMany(Remark::class, 'user');
    }

    /**
     * Collection of likes of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->remarks()->where('type', Remark::LIKE);
    }

    /**
     * Collection of dislikes of this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function dislikes()
    {
        return $this->remarks()->where('type', Remark::DISLIKE);
    }

    /**
     * Add a like for the given model by this user.
     *
     * @param mixed $model
     * @return void
     */
    public function like($model)
    {
        app(Remarks::class)->add($model, Remark::LIKE, $this);
    }

    /**
     * Remove a like from the given model for this user.
     *
     * @param mixed $model
     * @return void
     */
    public function unlike($model)
    {
        app(Remarks::class)->remove($model, Remark::LIKE, $this);
    }

    /**
     * Toggle like for the given model by this user.
     *
     * @param mixed $model
     * @return void
     */
    public function toggleLike($model)
    {
        app(Remarks::class)->toggle($model, Remark::LIKE, $this);
    }

    /**
     * Has the user already remarked the given model.
     *
     * @param mixed $user
     * @return bool
     */
    public function remarked($model)
    {
        return app(Remarks::class)->has($model, Remark::ALL, $this);
    }

    /**
     * Has the user already liked the given model.
     *
     * @param mixed $user
     * @return bool
     */
    public function liked($model)
    {
        return app(Remarks::class)->has($model, Remark::LIKE, $this);
    }

    /**
     * Delete remarks related to the current user.
     *
     * @return void
     */
    public function clearRemarks()
    {
        app(Remarks::class)->clearByUser(Remark::ALL, $this);
    }

    /**
     * Delete likes related to the current user.
     *
     * @return void
     */
    public function clearLikes()
    {
        app(Remarks::class)->clearByUser(Remark::LIKE, $this);
    }

    /**
     * Add a dislike for the given model by this user.
     *
     * @param mixed $model
     * @return void
     */
    public function dislike($model)
    {
        app(Remarks::class)->add($model, Remark::DISLIKE, $this);
    }

    /**
     * Remove a dislike from the given model for this user.
     *
     * @param mixed $model
     * @return void
     */
    public function undislike($model)
    {
        app(Remarks::class)->remove($model, Remark::DISLIKE, $this);
    }

    /**
     * Toggle dislike for the given model by this user.
     *
     * @param mixed $model
     * @return void
     */
    public function toggleDislike($model)
    {
        app(Remarks::class)->toggle($model, Remark::DISLIKE, $this);
    }

    /**
     * Has the user already disliked the given model.
     *
     * @param mixed $user
     * @return bool
     */
    public function disliked($model)
    {
        return app(Remarks::class)->has($model, Remark::DISLIKE, $this);
    }

    /**
     * Delete dislikes related to the current user.
     *
     * @return void
     */
    public function clearDislikes()
    {
        app(Remarks::class)->clearByUser(Remark::DISLIKE, $this);
    }

    /**
     * User remarksCount attribute.
     *
     * @return int
     */
    public function getRemarksCountAttribute()
    {
        return $this->remarks()->count();
    }

    /**
     * User likesCount attribute.
     *
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * User dislikesCount attribute.
     *
     * @return int
     */
    public function getDislikesCountAttribute()
    {
        return $this->dislikes()->count();
    }
}