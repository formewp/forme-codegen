<?php

namespace Forme\CodeGen\Source\Models;

use Forme\Framework\Models\CustomPostable;
use Forme\Framework\Models\Post;

final class Model extends Post
{
    // use CustomPostable - this will get added dynamically

    protected static $postType = 'cpt_placeholder';
}
