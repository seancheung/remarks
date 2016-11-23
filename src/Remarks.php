<?php

namespace Panoscape\Remarks;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Remarks
{
    /**
     * Add a remark for a model by a user.
     *
     * @param mixed $model
     * @param integer $type remark type
     * @param mixed $user If null will use currently logged in user.
     *
     * @return void
     */
    public function add($model, $type, $user = null)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE])) {
            throw new Exception("Invalid remark type");            
        }
        $user = $this->getUser($user);
        
        $remark = $model->morphMany(Remark::class, 'model')->where([
            'user_id' => $user->getKey(), 
            'user_type' => $user->getMorphClass(),
        ])->first();
        
        if(!is_null($remark)) {
            if($remark->type == $type) {
                return;
            }
            $remark->delete();            
        }

        $model->morphMany(Remark::class, 'model')->create([
                'user_id' => $user->getKey(), 
                'user_type' => $user->getMorphClass(),
                'type' => $type,
                'created_at' => time(),
            ]);
    }

    /**
     * Remove a remark for a model by a user.
     *
     * @param mixed $model
     * @param integer $type remark type
     * @param mixed $user If null will use currently logged in user.
     *
     * @return void
     */
    public function remove($model, $type, $user = null)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE, Remark::ALL])) {
            throw new Exception("Invalid remark type");            
        }
        $user = $this->getUser($user);

        if($type == Remark::ALL) {
            $model->morphMany(Remark::class, 'model')->where([
                'user_id' => $user->getKey(), 
                'user_type' => $user->getMorphClass(),
            ])->delete();
            return;
        }

        $remark = $model->morphMany(Remark::class, 'model')->where([
            'user_id' => $user->getKey(), 
            'user_type' => $user->getMorphClass(),
            'type' => $type,
        ])->first();

        if(!is_null($remark)) {
            $remark->delete();
        }
    }

    /**
     * Remove all remarks from the model
     *
     * @param mixed $model
     * @param integer $type remark type
     *
     * @return void
     */
    public function clearByModel($model, $type)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE, Remark::ALL])) {
            throw new Exception("Invalid remark type");            
        }

        $query = [
            'model_id' => $model->getKey(),
            'model_type' => $model->getMorphClass(),
            'type' => $type,
        ];

        if($type == Remark::ALL) {
            array_pop($query);
        }

        Remark::where($query)->delete();
    }

    /**
     * Remove all remarks made by the user
     *
     * @param integer $type remark type
     * @param mixed $user If null will use currently logged in user.
     *
     * @return void
     */
    public function clearByUser($type, $user = null)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE, Remark::ALL])) {
            throw new Exception("Invalid remark type");            
        }

        $query = [
            'user_id' => $user->getKey(),
            'user_type' => $user->getMorphClass(),
            'type' => $type,
        ];

        if($type == Remark::ALL) {
            array_pop($query);
        }

        Remark::where($query)->delete();
    }

    /**
     * Toggle a remark for a model by a user.
     *
     * @param mixed $model
     * @param integer $type remark type
     * @param mixed $user If null will use currently logged in user.
     *
     * @return void
     */
    public function toggle($model, $type, $user = null)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE])) {
            throw new Exception("Invalid remark type");            
        }
         $user = $this->getUser($user);

         $remark = $model->morphMany(Remark::class, 'model')->where([
            'user_id' => $user->getKey(),
            'user_type' => $user->getMorphClass(),
            'type' => $type,
         ])->exists();

         if($remark) {
             $this->remove($model, $type, $user);
         }
         else {
             $this->add($model, $type, $user);
         }
    }

    /**
     * Check if a remark on a model is made by a user
     *
     * @param mixed $model
     * @param integer $type remark type
     * @param mixed $user If null will use currently logged in user.
     *
     * @return bool
     */
    public function has($model, $type, $user = null)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE, Remark::ALL]) ) {
            throw new Exception("Invalid remark type");            
        }
        $user = $this->getUser($user);

        $query = [
            'user_id' => $user->getKey(),
            'user_type' => $user->getMorphClass(),
            'type' => $type,
        ];

        if($type == Remark::ALL) {
            array_pop($query);
        }

        return $model->morphMany(Remark::class, 'model')->where($query)->exists();
    }

    /**
     * Query scope
     *
     * @param mixed $query
     * @param integer $type remark type
     * @param mixed $user If null will use currently logged in user.
     *
     * @return mixed
     */
    public function scopeWhereRemarkedBy($query, $type, $user = null)
    {
        if(!in_array($type, [Remark::LIKE, Remark::DISLIKE, Remark::ALL]) ) {
            throw new Exception("Invalid remark type");            
        }
        $user = $this->getUser($user);

        $array = [
            'user_id' => $user->getKey(),
            'user_type' => $user->getMorphClass(),
            'type' => $type,
        ];

        if($type == Remark::ALL) {
            array_pop($query);
        }

        return $query->whereHas('remarks', function($q) use($array) {
            $q->where($array);
        });
    }

    protected function getUser($user)
    {
        if(is_null($user)) {
            if(!auth()->check()) {
                throw new Exception("No user specified");
            }
            return auth()->user();
        }

        if($user instanceof Model) {
            return $user;
        }

        throw new Exception("user must be Eloquent|null");        
    }
}