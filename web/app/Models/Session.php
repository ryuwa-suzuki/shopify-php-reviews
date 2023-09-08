<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shopify\Auth\Session as AuthSession;

class Session extends Model
{
    use HasFactory;

    public function instantiateAuthSession()
    {
        $authSession = new AuthSession(
            $this->id,
            $this->shop,
            $this->is_online,
            $this->state
        );
        $authSession->setAccessToken($this->access_token);

        return $authSession;
    }
}
