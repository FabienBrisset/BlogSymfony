<?php

namespace AngryProgrammers\BlogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AngryProgrammersBlogBundle extends Bundle
{
	    //declare bundle as a child of the FOSUserBundle so we can override the parent bundle's templates
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
