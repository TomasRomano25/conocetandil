<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validate(Request $request)
    {
        $code   = trim($request->input('code', ''));
        $type   = $request->input('type', 'membership'); // membership | hotel
        $planId = $request->input('plan_id') ? (int) $request->input('plan_id') : null;
        $amount = (float) $request->input('amount', 0);

        if (empty($code)) {
            return response()->json(['valid' => false, 'message' => 'Ingresá un código.']);
        }

        $promo = Promotion::byCode($code)->first();

        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Código inválido.']);
        }

        $userId = auth()->id() ?? 0;
        $result = $promo->validateForCheckout($type, $planId, $amount, $userId);

        return response()->json($result);
    }
}
