<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload an image to storage
     */
    public static function upload(UploadedFile $image, $folder = 'products', $oldImage = null)
    {
        // Delete old image if exists
        if ($oldImage && !filter_var($oldImage, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($oldImage);
        }
        
        // Generate unique filename
        $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
        
        // Store image
        $path = $image->storeAs($folder, $filename, 'public');
        
        return $path;
    }
    
    /**
     * Delete an image from storage
     */
    public static function delete($imagePath)
    {
        if ($imagePath && !filter_var($imagePath, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
    
    /**
     * Get default image based on type
     */
    public static function getDefaultImage($type = 'product')
    {
        $defaults = [
            'product' => 'images/default-product.png',
            'warehouse' => 'images/default-warehouse.png',
            'category' => 'images/default-category.png',
        ];
        
        return asset($defaults[$type] ?? $defaults['product']);
    }
}