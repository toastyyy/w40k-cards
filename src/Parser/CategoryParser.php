<?php

namespace App\Parser;

use App\Entity\Category;

class CategoryParser
{
    public function parse($data): Category {
        $category = new Category();
        $category->setText($data['@attributes']['name']);
        return $category;
    }
}
