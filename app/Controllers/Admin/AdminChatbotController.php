<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Request;
use App\Repositories\ChatbotKnowledgeRepository;

class AdminChatbotController
{
    private ChatbotKnowledgeRepository $repo;

    public function __construct()
    {
        $this->repo = new ChatbotKnowledgeRepository();
    }

    /**
     * Display chatbot knowledge entries list
     */
    public function index(): void
    {
        $items = $this->repo->all();

        echo View::render('admin/chatbot/index', [
            'title' => 'Chatbot Knowledge Base | HOMEIQ Admin',
            'items' => $items,
        ], 'admin');
    }

    /**
     * Store new knowledge entry
     */
    public function store(Request $request): void
    {
        $category = trim((string)$request->post('category', 'General'));
        $question = trim((string)$request->post('question', ''));
        $answer = trim((string)$request->post('answer', ''));
        $keywords = trim((string)$request->post('keywords', ''));
        $linkUrl = trim((string)$request->post('link_url', ''));
        $linkLabel = trim((string)$request->post('link_label', ''));

        if ($question === '' || $answer === '') {
            flash('error', 'Question and Answer are both required.');
            redirect('admin/chatbot');
            return;
        }

        $this->repo->create([
            'category' => $category,
            'question' => $question,
            'answer' => $answer,
            'keywords' => $keywords,
            'link_url' => $linkUrl,
            'link_label' => $linkLabel,
            'active' => true,
        ]);

        flash('success', 'New knowledge entry added to Chatbot Assistant.');
        redirect('admin/chatbot');
    }

    /**
     * Update knowledge entry
     */
    public function update(Request $request, string $id): void
    {
        $category = trim((string)$request->post('category', 'General'));
        $question = trim((string)$request->post('question', ''));
        $answer = trim((string)$request->post('answer', ''));
        $keywords = trim((string)$request->post('keywords', ''));
        $linkUrl = trim((string)$request->post('link_url', ''));
        $linkLabel = trim((string)$request->post('link_label', ''));

        if ($question === '' || $answer === '') {
            flash('error', 'Question and Answer cannot be empty.');
            redirect('admin/chatbot');
            return;
        }

        $this->repo->update($id, [
            'category' => $category,
            'question' => $question,
            'answer' => $answer,
            'keywords' => $keywords,
            'link_url' => $linkUrl,
            'link_label' => $linkLabel,
        ]);

        flash('success', 'Chatbot knowledge entry updated successfully.');
        redirect('admin/chatbot');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, string $id): void
    {
        $newStatus = $this->repo->toggle($id);
        if ($newStatus !== null) {
            flash('success', 'Knowledge entry ' . ($newStatus ? 'enabled' : 'disabled') . '.');
        } else {
            flash('error', 'Entry not found.');
        }
        redirect('admin/chatbot');
    }

    /**
     * Delete entry
     */
    public function delete(Request $request, string $id): void
    {
        if ($this->repo->delete($id)) {
            flash('success', 'Knowledge entry removed from Chatbot.');
        } else {
            flash('error', 'Unable to delete entry.');
        }
        redirect('admin/chatbot');
    }
}
