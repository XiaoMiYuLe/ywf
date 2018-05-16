<?php

function smarty_modifier_implode($value, $pieces)
{
    return is_array($value) ? implode($pieces, $value) : '';
} 

