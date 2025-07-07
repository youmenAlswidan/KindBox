<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GroupOrder;
use App\Models\GroupOrderMember;
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;

class NotifyUsersIncompleteGroupOrders extends Command
{
    protected $signature = 'group-orders:notify-incomplete';
    protected $description = 'Notify users about incomplete group orders when the deadline has passed';

    public function handle()
    {
        $this->info('🔄 بدء فحص الجروبات المنتهية بدون اكتمال...');

        $now = Carbon::now();

        $groupOrders = GroupOrder::where('dead_line', '<=', $now)
            ->where('status', '!=', 'completed')
            ->get();

        $count = 0;

        foreach ($groupOrders as $groupOrder) {
            if ($groupOrder->current_user_count < $groupOrder->require_user_count) {

                $members = GroupOrderMember::where('group_order_id', $groupOrder->id)->get();

                foreach ($members as $member) {
                    NotificationController::notifyUser(
                        $member->user_id,
                        "انتهت مهلة الشراء الجماعي للمنتج ({$groupOrder->product->name}) ولم يكتمل العدد، يرجى إكمال الفرق للسعر الأصلي لإتمام طلبك."
                    );
                    $count++;
                }

                // يمكنك هنا تحديث حالة الجروب إلى expired مثلاً لتعرف أنه تم الإشعار
                $groupOrder->update(['status' => 'expired']);
            }
        }

        $this->info("✅ تم إرسال إشعارات إلى {$count} مستخدم/مستخدمين حول الجروبات المنتهية غير المكتملة.");
    }
}
