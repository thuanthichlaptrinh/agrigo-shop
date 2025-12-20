<?php

namespace App\Events;

use App\Models\TinNhan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $message;
    public int $conversationId;

    public function __construct(TinNhan $tinNhan)
    {
        $this->conversationId = $tinNhan->IDCuocHoiThoai;
        $this->message = [
            'id' => $tinNhan->ID,
            'conversation_id' => $tinNhan->IDCuocHoiThoai,
            'sender_id' => $tinNhan->IDNguoiGui,
            'sender_type' => $tinNhan->LoaiNguoiGui,
            'sender_name' => $tinNhan->nguoiGui?->TenNguoiDung ?? 'Hệ thống',
            'sender_avatar' => $tinNhan->nguoiGui?->HinhAnh,
            'content' => $tinNhan->NoiDung,
            'image' => $tinNhan->HinhAnh,
            'sent_at' => $tinNhan->ThoiGianGui->format('Y-m-d H:i:s'),
            'is_read' => $tinNhan->DaXem,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.conversation.' . $this->conversationId),
            new PrivateChannel('chat.admin'), // Admin nhận tất cả tin nhắn mới
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.new';
    }
}
