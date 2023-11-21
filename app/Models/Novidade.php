<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Novidade
 * Table/View: novidades
 *
 * @version September 11, 2023, 14:46 am -03
 *
 * @property int $id
 * @property int $empresa_id
 * @property string $title
 * @property string $description
 * @property int $type
 * @property string $version
 * @property string $link
 * @property string $arquivo
 */
class Novidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'version',
        'link',
        'arquivo',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'type' => 'integer',
        'version' => 'string',
        'link' => 'string',
        'arquivo' => 'string',
    ];
}
