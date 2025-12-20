<?php

namespace App\Events;

use App\Models\CuocHoiThoai;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $conversation;
    public string $action;

    public function __construct(CuocHoiThoai $cuocHoiThoai, string $action = 'updated')
    {
        $this->action = $action;
        $this->conversation = [
            'id' => $cuocHoiThoai->ID,
            'user_id' => $cuocHoiThoai->IDNguoiDung,
            'user_name' => $cuocHoiThoai->nguoiDung?->TenNguoiDung ?? 'Khách',
            'user_avatar' => $cuocHoiThoai->nguoiDung?->HinhAnh,
            'admin_id' => $cuocHoiThoai->IDAdmin,
            'admin_name' => $cuocHoiThoai->admin?->TenNguoiDung,
            'title' => $cuocHoiThoai->TieuDe,
            'status' => $cuocHoiThoai->TrangThai,
            'last_activity' => $cuocHoiThoai->LanHoatDongCuoi?->format('Y-m-d H:i:s'),
            'last_message' => $cuocHoiThoai->tinNhanMoiNhat?->NoiDung,
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.admin'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'conversation.' . $this->action;
    }
}
