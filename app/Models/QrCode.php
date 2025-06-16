<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class QrCode extends Model
{
	protected $table = 'qr_codes';

    protected $fillable = ['user_id', 'type', 'qr_code', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the QR code image path based on object ID and object type.
     *
     * @param int $id         The object ID.
     * @param string $objectType The object type.
     * @return string|null    The QR code image path if found, or null if not found.
     */
    public static function getQrCode($id, $objectType = 'user', $selected = [])
    {
        $query = self::where(['object_id' => $id, 'object_type' => $objectType, 'status' => 'Active']);
        !empty($selected) ? $query->select($selected) : $query->select('*');

        return $query->first();
    }

    /**
     * Create User QR code
     *
     * @param object $user
     * @return void
     */
    public static function createUserQrCode($user)
    {
        try {
            $imageName = time() . '.' . 'jpg';

            $formattedPhone = $user->formattedPhone ?? '';
            $secretCode = convert_string('encrypt', 'user' . '-' . $user->email . '-' . $formattedPhone . '-' . Str::random(6));
            
            $qrCode = new self();
            $qrCode->object_id = $user->id;
            $qrCode->object_type = 'user';
            $qrCode->secret = $secretCode;
            $qrCode->qr_image = $imageName;
            $qrCode->status = 'Active';
            $qrCode->save();

            $secretCodeImage = generateQrcode($qrCode->secret);
            $imageContent = self::getImageFromUrl($secretCodeImage);
            // Image::make($secretCodeImage)->save(getDirectory('user_qrcode') . $imageName);
            Image::make($imageContent)->save(getDirectory('user_qrcode') . $imageName);
            
            return $qrCode;
        } catch( Exception $e ) {
            return $e->getMessage();
        }
    }

    public static function updateQrCode($user)
    {
        $qrCode  = self::where(['object_id' => $user->id, 'object_type' => 'user', 'status' => 'Active'])->first(['id', 'secret']);
        if (!empty($qrCode)) {
            $qrCode->status = 'Inactive';
            $qrCode->save();
        }

        return self::createUserQrCode($user);
    }

    /**
     * Added by Shimul
     * This will Download Image from QR Code URL
     */
    protected static function getImageFromUrl($url) {
        if (!ini_get('allow_url_fopen') && function_exists('curl_version')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $content = curl_exec($curl);
            curl_close($curl);
            Log::debug("Download image using CURL");
            return $content;
        } else if (ini_get('allow_url_fopen')) {
            $content = file_get_contents($url);
            Log::debug("Download image using File Get Contents");
            return $content;
        } else {
            Log::error("Unable to download image");
            return null;
        }
    }
}
