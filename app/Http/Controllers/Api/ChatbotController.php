<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use App\Models\LoaiSanPham;
use App\Models\SanPham;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Chat endpoint powered by Gemini 2.5-flash.
     * It builds lightweight context from DB + FAQ and forwards user prompt.
     */
    public function query(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'array',
            'history.*.role' => 'required|string|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json([
                'error' => 'Thiếu cấu hình GEMINI_API_KEY',
            ], 500);
        }

        // Build context from DB (fast, limited rows)
        $categories = LoaiSanPham::query()
            ->select(['ID', 'TenLoai'])
            ->orderByDesc('ID')
            ->limit(8)
            ->get()
            ->map(fn($c) => "- {$c->TenLoai} (ID: {$c->ID})")
            ->implode("\n");

        $catalogs = DanhMuc::query()
            ->select(['ID', 'TenDanhMuc'])
            ->orderBy('TenDanhMuc')
            ->limit(6)
            ->get()
            ->map(fn($c) => "- {$c->TenDanhMuc}")
            ->implode("\n");

        $products = SanPham::query()
            ->select(['ID', 'TenSanPham', 'Gia'])
            ->orderByDesc('ID')
            ->limit(6)
            ->get()
            ->map(fn($p) => "- {$p->TenSanPham} (ID {$p->ID}) giá khoảng {$p->Gia} đ")
            ->implode("\n");

        $faq = $this->loadFaq();

        $systemPrompt = <<<PROMPT
Bạn là trợ lý mua sắm cho cửa hàng nông sản. Trả lời ngắn gọn, rõ ràng bằng tiếng Việt.
Nếu câu hỏi liên quan sản phẩm/danh mục, ưu tiên dựa trên dữ liệu sau:

[Danh mục lớn]
{$catalogs}

[Loại sản phẩm]
{$categories}

[Một số sản phẩm]
{$products}

[FAQ]
{$faq}

Nếu không chắc, hãy hỏi lại hoặc đề xuất người dùng để lại số điện thoại/email.
PROMPT;

        // Build messages in Gemini format
        $historyParts = collect($validated['history'] ?? [])
            ->take(10)
            ->map(fn($h) => [
                'role' => $h['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $h['content']]],
            ]);

        $messages = $historyParts->push([
            'role' => 'user',
            'parts' => [['text' => $validated['message']]],
        ])->values();

        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->withQueryParameters(['key' => $apiKey])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
                    [
                        'contents' => $messages,
                        'system_instruction' => [
                            'parts' => [['text' => $systemPrompt]],
                        ],
                    ]
                );

            if (!$response->ok()) {
                Log::warning('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json([
                    'error' => 'Chat service tạm thời gián đoạn. Vui lòng thử lại.',
                ], 502);
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            return response()->json([
                'reply' => $text ?: 'Mình chưa lấy được câu trả lời, bạn thử lại giúp mình nhé.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini call failed', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Không thể kết nối chatbot lúc này. Vui lòng thử lại.',
            ], 500);
        }
    }

    private function loadFaq(): string
    {
        $path = base_path('storage/app/faq.json');
        if (!file_exists($path)) {
            return '';
        }

        $items = json_decode(file_get_contents($path), true);
        if (!is_array($items)) {
            return '';
        }

        return collect($items)
            ->take(20)
            ->map(function ($item) {
                $q = $item['question'] ?? '';
                $a = $item['answer'] ?? '';
                return "Q: {$q}\nA: {$a}";
            })
            ->implode("\n\n");
    }
}
