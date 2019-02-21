<?php

namespace iBrand\Component\Vip\Test;

use Carbon\Carbon;

class VipTest extends BaseTest
{
	public function testGetOrderByNo()
	{
		$order = $this->vipOrderRepository->getOrderByNo($this->order_no);

		$this->assertSame($order->count(), 1);

		return $order;
	}

	/**
	 * @depends testGetOrderByNo
	 */
	public function testGetPlansByUserId($order)
	{
		$order->status  = 2;
		$order->paid_at = Carbon::now();
		$order->save();

		$this->vipMemberRepository->create([
			'plan_id'   => $order->plan_id,
			'user_id'   => $order->user_id,
			'order_id'  => $order->id,
			'joined_at' => Carbon::now(),
		]);

		$member = $this->vipMemberRepository->getDefaultPlanByUserId(1);
		$this->assertSame($member->count(), 1);

		$list = $this->vipMemberRepository->getPlansByUserId(1);
		$this->assertSame($list->count(), 1);
	}

	public function testPlan()
	{
		$plan = $this->vipPlanRepository->find(1);

		$this->assertArrayHasKey('free_course', $plan->actions);
		$this->assertArrayHasKey('course_discount_percentage', $plan->actions);
		$this->assertSame($plan->price, 666);
	}
}