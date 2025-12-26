<?php

namespace App\Enums;

enum CommentTypeEmoji: string
{
    case INTELLIGENT = 'intelligent';
    case DROLE = 'drole';
    case DEFAULT = 'default';

    public static function getEmoji(string $name): string
    {
        $lowerName = strtolower($name);

        return match (true) {
            str_contains($lowerName, 'intelligent') => '🧠',
            str_contains($lowerName, 'drole') || str_contains($lowerName, 'drôle') => '😂',
            default => '💬',
        };
    }
}
