<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LienHe;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function show()
    {
        return view('user.contact');
    }

    public function store(Request $request)
    {
        if ($request->input('form_type') === 'launcher-contact') {
            $data = $request->validate([
                'SDT' => ['required', 'string', 'max:20'],
                'Product' => ['nullable', 'string', 'max:150'],
                'NoiDung' => ['required', 'string', 'max:2000'],
            ], [
                'SDT.required' => 'Vui lòng nhập số điện thoại',
                'NoiDung.required' => 'Vui lòng nhập tin nhắn',
            ]);

            $payload = [
                'HoTen' => $request->input('HoTen', 'Khách chat'),
                'Email' => $request->input('Email', 'widget@organicshop.local'),
                'SDT' => $data['SDT'],
                'TieuDe' => $request->input('TieuDe')
                    ?: ($request->input('Product')
                        ? 'Tư vấn về ' . $request->input('Product')
                        : 'Yêu cầu tư vấn từ chatbox'),
                'NoiDung' => $data['NoiDung'],
                'TrangThai' => 'Mới',
            ];

            LienHe::create($payload);

            $message = 'Đã nhận yêu cầu tư vấn. Chúng tôi sẽ liên hệ sớm.';

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'type' => 'success',
                ]);
            }

            return redirect()->route('user.contact.show')->with([
                'success' => $message,
            ]);
        }

        $data = $request->validate([
            'HoTen' => ['required', 'string', 'max:120'],
            'Email' => ['required', 'email', 'max:150'],
            'SDT' => ['nullable', 'string', 'max:20'],
            'TieuDe' => ['required', 'string', 'max:150'],
            'NoiDung' => ['required', 'string', 'max:2000'],
        ], [
            'HoTen.required' => 'Vui lòng nhập họ và tên',
            'Email.required' => 'Vui lòng nhập email',
            'Email.email' => 'Email không hợp lệ',
            'TieuDe.required' => 'Vui lòng nhập tiêu đề',
            'NoiDung.required' => 'Vui lòng nhập nội dung',
        ]);

        $data['TrangThai'] = 'Mới';
        LienHe::create($data);

        $message = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'type' => 'success',
            ]);
        }

        return redirect()->route('user.contact.show')->with([
            'success' => $message,
        ]);
    }
}
