<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UniqueCode extends Model
{
    use HasFactory;

    public static function generateRmCode()
    {
        // Fetch the last inserted code from the raw_materials table
        $lastCode = \DB::table('raw_materials')->orderBy('id', 'desc')->value('rmcode');

        // Extract the numeric part of the code
        $number = $lastCode ? intval(substr($lastCode, 2)) : 0;

        // Increment the number and format it with leading zeros
        $newNumber = str_pad($number + 1, 4, '0', STR_PAD_LEFT);

        // Return the formatted code
        return 'RM' . $newNumber;
    }

    public static function generatePmCode()
    {
        // Fetch the last inserted code from the raw_materials table
        $lastCode = \DB::table('packing_materials')->orderBy('id', 'desc')->value('pmcode');

        // Extract the numeric part of the code
        $number = $lastCode ? intval(substr($lastCode, 2)) : 0;

        // Increment the number and format it with leading zeros
        $newNumber = str_pad($number + 1, 4, '0', STR_PAD_LEFT);

        // Return the formatted code
        return 'PM' . $newNumber;
    }

    public static function generateOhCode()
    {
        // Fetch the last inserted code from the raw_materials table
        $lastCode = \DB::table('overheads')->orderBy('id', 'desc')->value('ohcode');

        // Extract the numeric part of the code
        $number = $lastCode ? intval(substr($lastCode, 2)) : 0;

        // Increment the number and format it with leading zeros
        $newNumber = str_pad($number + 1, 4, '0', STR_PAD_LEFT);

        // Return the formatted code
        return 'OH' . $newNumber;
    }

    public static function generatePdCode()
    {
        // Fetch the last inserted code from the raw_materials table
        $lastCode = \DB::table('product_master')->orderBy('id', 'desc')->value('pdcode');

        // Extract the numeric part of the code
        $number = $lastCode ? intval(substr($lastCode, 2)) : 0;

        // Increment the number and format it with leading zeros
        $newNumber = str_pad($number + 1, 4, '0', STR_PAD_LEFT);

        // Return the formatted code
        return 'PD' . $newNumber;
    }
    public static function generateRpCode()
    {
        // Fetch the last inserted code from the raw_materials table
        $lastCode = \DB::table('recipe_master')->orderBy('id', 'desc')->value('rpcode');

        // Extract the numeric part of the code
        $number = $lastCode ? intval(substr($lastCode, 2)) : 0;

        // Increment the number and format it with leading zeros
        $newNumber = str_pad($number + 1, 4, '0', STR_PAD_LEFT);

        // Return the formatted code
        return 'RP' . $newNumber;
    }
}
