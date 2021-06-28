<?php

if (!function_exists("isInBeta")) {
    function isInBeta()
    {
        $a = auth();
        if (is_null($a)) {
            return false;
        }

        switch ($a->id()) {
            case 343: /* Reza */
            case 316: /* Tracy Berman */
            case 173: /* John Allison */
            case 101: /* David Montiel */
            case 130: /* Glenn Tarcea */
            case 65:  /* Brian Puchala */
                return true;
            default:
                return false;
        }
    }
}

