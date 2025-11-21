<?php

namespace App\Support\Traits;

use Illuminate\Support\Str;

trait AccentInsensitiveSearch
{
    protected function normalizeKeyword(string $keyword): string
    {
        return Str::lower(Str::ascii($keyword));
    }

    protected function accentInsensitiveColumn(string $column): string
    {
        $map = [
            'a' => ['à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ'],
            'e' => ['è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ'],
            'i' => ['ì','í','ị','ỉ','ĩ'],
            'o' => ['ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ'],
            'u' => ['ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ'],
            'y' => ['ỳ','ý','ỵ','ỷ','ỹ'],
            'd' => ['đ'],
        ];

        $expr = "LOWER({$column})";
        foreach ($map as $base => $chars) {
            foreach ($chars as $char) {
                $expr = "REPLACE({$expr}, '{$char}', '{$base}')";
            }
        }

        return $expr;
    }
}
