<?php

namespace Panoscape\Remarks;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    /**
     * like type
     */
    const LIKE = 1;

    /**
     * dislike type
     */
    const DISLIKE = 2;

    /**
     * like or dislike type
     */
    const ALL = 0; 
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'remarks_remarks';

    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
    public $timestamps = false;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['created_at'];
     
    /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Likeable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * User relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo();
    }
}