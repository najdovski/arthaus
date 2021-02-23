<?php

namespace App\Helpers;

class AppHelper
{
    /**
     * Random lorem ipsum text
     */
    public static function randomLoremIpsum()
    {
        $loremText = 'Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Itaque delectus nobis veritatis, alias suscipit deleniti praesentium
            et libero odio earum blanditiis. Error vel quo ipsum hic earum!
            Eos itaque magni reiciendis nulla deserunt cumque distinctio unde aut!
            Sint veniam animi cum aperiam illo fugiat! Voluptate quasi dicta eaque
            odio provident?';
        
        $randomEnd = rand(15, strlen($loremText));
        return substr($loremText, 0, $randomEnd);
    }
}
