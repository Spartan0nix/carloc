<?php

namespace App\Helper;

class BuildRentInfo
{
    /**
     * Helper that return the rentInfo array
     *
     * @param array $req
     * @return array
     */
    public function build(array $req){
        if(!$req['return_office']){
            $req['return_office'] = $req['pickup_office'];
        }
        
        $rentInfo = array();
        $rentInfo['pickup_office'] = $req['pickup_office'];
        $rentInfo['return_office'] = $req['return_office'];
        $rentInfo['start_date'] = $req['start_date'];
        $rentInfo['end_date'] = $req['end_date'];

        return $rentInfo;
    }
}