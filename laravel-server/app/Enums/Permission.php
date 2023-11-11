<?php

namespace App\Enums;

enum Permission: string
{
    case DELETE_BOOKS = 'deleteBooks';
    case DOWNLOAD_FREE = 'downloadFree';
}
