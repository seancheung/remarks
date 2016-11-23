# Remarks
Eloquent model like and dislike features for Laravel

## Installation

### Composer

```shell
composer require panoscape/remarks
```

### Service provider

> config/app.php

```php
'providers' => [
    ...
    Panoscape\Remarks\RemarksServiceProvider::class,
];
```

### Facades

> config/app.php

```php
'aliases' => [
    ...
    'Remarks' => Panoscape\Remarks\Facades\Remarks::class,
];
```

### Remark

> config/app.php

```php
'aliases' => [
    ...
    'App\Remark' => Panoscape\Remarks\Remark::class,
];
```

### Migration

```shell
php artisan vendor:publish --provider="Panoscape\Remarks\RemarksServiceProvider" --tag=migrations
```

Before migrating, you'll need to modify the `users` table in the published migration file to the correct user table used in your application

```php
//TODO: users table
$table->foreign('user_id')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
```

## Usage

Add `HasRemarks` trait to user model.

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Panoscape\Remarks\HasRemarks;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRemarks;
}
```

Add `Remarkable` trait to the model that can be remarked.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Panoscape\Remarks\Remarkable;

class Article extends Model
{
    use Remarkable;
}
```

### Get remarks of a user

```php
//get all remarks
$user->remakrs();
//or dynamic property
$user->remarks;

//get all likes
$user->likes();
//get all dislikes
$user->dislikes();
```

### Get remarks of a remarkable

```php
//get all remarks
$remarkable->remarks();
//or dynamic property
$remarkable->remarks;

//get all likes
$remarkable->likes();
//get all dislikes
$remarkable->dislikes();
```

### Check remark

```php
//check if a remarkable is liked by current user
$remarkable->liked();
//or dynamic property
$remarkable->liked;

//check if a remarkable is liked by a specific user
$remarkable->liked($user);

//same with dislike and remark
$remarkable->disliked();
$remarkable->disliked($user);
$remarkable->remarked($user);
$remarkable->remarked();

//check if a user likes a specific remarkable
$user->liked($remarkable);
//check if a user dislikes a specific remarkable
$user->disliked($remarkable);
//check if a user dislikes/likes a specific remarkable
$user->remarked($remarkable);

//count
$remarkable->likesCount;
$remarkable->dislikesCount;
$remarkable->remarksCount;
$user->likesCount;
$user->dislikesCount;
$user->remarksCount;
```

### Add/Remove/Clear remarks

```php
//add a like for model by current user
$remarkable->like();
//add a like for model by the given user
$remarkable->like($user);

//remove a like from this record for current user
$remarkable->unlike();
//remove a like from this record for the given user
$remarkable->unlike($user);
//clear likes
$remarkable->clearLikes();

//same with dislike
$remarkable->dislike();
$remarkable->dislike($user);
$remarkable->undislike();
$remarkable->undislike($user);
$remarkable->clearDislikes();
$remarkable->clearRemarks();

//on user
$user->like($remarkable);
$user->unlike($remarkable);
$user->dislike($remarkable);
$user->undislike($remarkable);
$user->clearLikes();
$user->clearDislikes();
$user->clearRemarks();

//toggle
$remarkable->toggleLike();
$remarkable->toggleLike($user);
$remarkable->toggleDislike();
$remarkable->toggleDislike($user);
$user->toggleLike($remarkable);
$user->toggleDislike($remarkable);
```

### Scope

```php
//query builder
$remarkable->whereLikedBy($user);
$remarkable->whereLikedBy();
$remarkable->whereDislikedBy($user);
$remarkable->whereDislikedBy();
$remarkable->whereRemarkedBy($user);
$remarkable->whereRemarkedBy();
```

### Remark

```php
//get the remarkable model
$remark->model();
//or dynamic property
$remark->model;

//get the user
$remark->user();
//or dynamic property
$remark->user;
```

### Remarks

```php
use Panoscape\Remarks\Facades\Remarks;
...

///add remark
Remarks::add($remarkable, Remark::LIKE, $user);
//remove
Remarks::remove($remarkable, Remark::LIKE, $user);
//toggle
Remarks::toggle($remarkable, Remark::LIKE, $user);
//check
Remarks::has($remarkable, Remark::LIKE, $user);
//clear
Remarks::clearByModel($remarkable, Remark::ALL);
```