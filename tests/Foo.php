<?php

namespace Railken\LaraEye\Tests;

use Illuminate\Database\Eloquent\Model;

class Foo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'x', 'y',
    ];
}
