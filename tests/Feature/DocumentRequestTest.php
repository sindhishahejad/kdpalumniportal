<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DocumentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_document_request(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/documents', [
            'document_type' => 'Official Transcript',
            'purpose' => 'Higher studies application to GTU.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('document_requests', [
            'user_id' => $user->id,
            'document_type' => 'Official Transcript',
            'status' => 'Pending',
        ]);
    }

    public function test_admin_can_update_status_and_upload_fulfilled_document(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $student = User::factory()->create(['role' => 'student', 'is_approved' => true]);

        $doc = DocumentRequest::create([
            'user_id' => $student->id,
            'document_type' => 'Bonafide Certificate',
            'purpose' => 'Passport verification',
            'status' => 'Pending',
        ]);

        $fakeFile = UploadedFile::fake()->create('bonafide.pdf', 200, 'application/pdf');

        $response = $this->actingAs($admin)->put("/admin/documents/{$doc->id}", [
            'status' => 'Ready',
            'admin_notes' => 'Generated and signed by principal.',
            'document_file' => $fakeFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $doc->refresh();
        $this->assertEquals('Ready', $doc->status);
        $this->assertNotNull($doc->file_path);
        Storage::disk('public')->assertExists($doc->file_path);

        // Assert notification was created for student
        $this->assertCount(1, $student->notifications);
    }

    public function test_user_can_download_their_fulfilled_document(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $fakePath = 'documents/test_transcript.pdf';
        Storage::disk('public')->put($fakePath, 'Sample PDF content');

        $doc = DocumentRequest::create([
            'user_id' => $user->id,
            'document_type' => 'Official Transcript',
            'status' => 'Ready',
            'file_path' => $fakePath,
        ]);

        $response = $this->actingAs($user)->get("/documents/{$doc->id}/download");
        $response->assertStatus(200);

        // Other user cannot download
        $otherUser = User::factory()->create();
        $forbiddenResponse = $this->actingAs($otherUser)->get("/documents/{$doc->id}/download");
        $forbiddenResponse->assertStatus(403);
    }
}
