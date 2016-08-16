<?php namespace Med\Entities\Traits;

trait ImageAlterationTrait
{

    /**
     * Return the full URL for the cloudinary asset.
     *
     * @param $image
     * @param string $transformations
     * @return string
     */
    public function fullCloudinary($image, $transformations = '')
    {
        $image = str_replace('http://', '', $image);
        $imageParts = collect(explode('/', $image));
        $beginningParts = $imageParts->slice(0, -3)->reject(function($item) {
            return str_contains($item, 'v1');
        });
        $endParts = $imageParts->slice(-3, 3)->prepend('v1');

        if ($transformations != '') {
            $transformations .=  '/';
        }

        $return = 'http://' . $beginningParts->implode('/') . '/' . $transformations . $endParts->implode('/');

        return $return;
    }
}
