<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDType extends Model
{
    use HasFactory;

    /**
     * Table associated with PDType model
     *
     * @var string
     */
    protected $table = 'p_d_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
    ];

    /**
     * pds relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pds()
    {
        return $this->hasMany(PD::class);
    }
}
