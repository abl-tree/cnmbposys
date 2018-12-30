<?php 

use App\Data\Models\BaseModel;
use App\BaseAuthModel;
use Illuminate\Database\Eloquent\Builder;

if (! function_exists('move_file')) {
    function move_file($file, $type='avatar', $withWatermark = false)
    {
        // Grab all variables
        $destinationPath = config('variables.'.$type.'.folder');
        $width           = config('variables.' . $type . '.width');
        $height          = config('variables.' . $type . '.height');
        $full_name       = str_random(16) . '.' . $file->getClientOriginalExtension();
        
        if ($width == null && $height == null) { // Just move the file
            $file->storeAs($destinationPath, $full_name);
            return $full_name;
        }


        // Create the Image
        $image           = Image::make($file->getRealPath());

        if ($width == null || $height == null) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }else{
            $image->fit($width, $height);
        }

        if ($withWatermark) {
            $watermark = Image::make(public_path() . '/img/watermark.png')->resize($width * 0.5, null);

            $image->insert($watermark, 'center');
        }

        Storage::put($destinationPath . '/' . $full_name, (string) $image->encode());

        return $full_name;
    }
}

function topBarName(){
    $id = Auth::id();
    $userinfo = \App\Data\Models\UserInfo::find($id);

    return $userinfo;
}

function isAdmin(){
    $access_level = auth()->user()->access_id;

    if($access_level == 1){
        return true;
    }
    else{
        return false;
    }
}

function isAdminHRM(){
    $access_level = auth()->user()->access_id;

    if($access_level == 1 || $access_level == 2 || $access_level == 3){
        return true;
    }
    else{
        return false;
    }
}

function isAdminHR(){
    $access_level = auth()->user()->access_id;

    if($access_level == 1 || $access_level == 2 || $access_level == 3){
        return true;
    }
    else{
        return false;
    }
}

function canIR(){
    $access_level = auth()->user()->access_id;

    if($access_level == 1 || $access_level == 2 || $access_level == 3){
        return true;
    }
    else{
        return false;
    }
}

if ( !function_exists ( "dump_query" ) ) {
    /**
     * @param Builder $model
     * @return string
     */
    function dump_query ( $model )
    {
        $copy = clone( $model );
        return $copy->getModel ()->getRawSql ( $copy );
    }
}


if( !function_exists( "refresh_model") ){
    /**
     * @param \App\Data\Models\BaseModel $model
     * @param array $data
     * @return mixed
     */
    /**
     * @param \App\BaseAuthModel $model
     * @param array $data
     * @return mixed
     */
    function refresh_model( $model, $data=[] ){
        $class = $model->getClass();
        $new_model = new $class( $data );

        if( !$new_model instanceof BaseModel && !$new_model instanceof BaseAuthModel ){
            return false;
        }

        return $new_model;
    }
}


