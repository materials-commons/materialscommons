<?php

namespace Tests\Feature\Actions\Notifications;

use App\Actions\Notifications\CreateNotificationAction;
use App\Models\Dataset;
use App\Models\User;
use Facades\Tests\Factories\DatasetFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateNotificationActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_add_a_dataset_notification_for_a_mc_user()
    {
        $user = User::factory()->create();
        $dataset = DatasetFactory::ownedBy($user)->create();
        $createNotificationAction = new CreateNotificationAction();
        $createNotificationAction->addNotificationForUser($user, $dataset);
        $this->assertDatabaseHas('notifications', [
            'owner_id'        => $user->id,
            'notifyable_type' => Dataset::class,
            'notifyable_id'   => $dataset->id,
        ]);

        $count = $dataset->notifications()->count();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_should_add_a_dataset_notification_for_a_non_mc_user()
    {
        $dataset = DatasetFactory::create();
        $createNotificationAction = new CreateNotificationAction();
        $createNotificationAction->addNotificationForEmail('bob@bob.com', 'Bob Test', $dataset);
        $this->assertDatabaseHas('notifications', [
            'email'           => 'bob@bob.com',
            'name'            => 'Bob Test',
            'notifyable_type' => Dataset::class,
            'notifyable_id'   => $dataset->id,
        ]);

        $count = $dataset->notifications()->count();
        $this->assertEquals(1, $count);
    }
}
