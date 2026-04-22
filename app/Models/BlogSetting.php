<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['site_title', 'site_description', 'fav_icon', 'logo'])]
class BlogSetting extends Model
{
    //
}
