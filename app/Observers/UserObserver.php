<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Customer;

class UserObserver
{
public function created(User $user)
{
Customer::create([
'user_id' => $user->id,
'name' => $user->name,
'email' => $user->email,
// Čia pridėkite kitus laukus, jei norite, pvz., phone, company (gal default reikšmės)
]);
}
}
