<?php

namespace App\Livewire;

use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CourseComments extends Component
{
    use AuthorizesRequests;
    public $approved_comments = [];
    public $comments = [];
    public $videoId;
    public $isUser;
    public $isAdmin;
    public $newComment = '';
    public $currentPage = 1;
    public $itemsPerPage = 10;

    public function mount($videoId, $isUser = false)
    {
        $this->videoId = $videoId;
        $this->isUser = $isUser;
        $this->isAdmin = $isUser ? false : true;

        $this->loadComments();
    }

    public function loadComments()
    {
        if ($this->videoId > 0){
            $this->approved_comments = Comment::where('video_id', $this->videoId)
                ->where('is_approved', true)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();

            $this->comments = Comment::where('video_id', $this->videoId)
                ->where('is_approved', false)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        }else{
            $this->approved_comments = [];
            $this->comments = Comment::where('is_approved', false)
                ->with('user')
                ->get()
                ->toArray();
        }
    }

    public function createComment()
    {
        if (!trim($this->newComment)) {
            session()->flash('error', 'El comentario no puede estar vacío.');
            $this->dispatch('flashMessageUpdatedError');
            return;
        }

        $comment = Comment::create([
            'content' => $this->newComment,
            'video_id' => $this->videoId,
            'user_id' => auth()->id(),
            'is_approved' => false,
        ]);

        $this->comments = [$comment->load('user')->toArray(), ...$this->comments];
        $this->newComment = '';

        session()->flash('success', 'Tu comentario esta en espera de aprobación.');
        $this->dispatch('flashMessageUpdated');
    }

    public function approveComment($commentId)
    {
        $comment = Comment::find($commentId);
        // Verificar autorización
        $this->authorize('approve', $comment);
        $comment->update(['is_approved' => true]);

        $this->loadComments();
        session()->flash('success', 'Comentario aprobado correctamente.');
        $this->dispatch('flashMessageUpdated');
    }

    public function declineComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Verificar autorización
        $this->authorize('decline', $comment);

        $comment->delete();

        $this->loadComments();
        session()->flash('success', 'Comentario rechazado correctamente.');
        $this->dispatch('flashMessageUpdated');
    }

    public function render()
    {
        $start = ($this->currentPage - 1) * $this->itemsPerPage;
        $paginatedComments = array_slice($this->approved_comments, $start, $this->itemsPerPage);
        $this->comments = array_slice($this->comments, $start, $this->itemsPerPage);

        return view('livewire.course-comments', [
            'paginatedComments' => $paginatedComments,
            'totalPages' => ceil(count($this->approved_comments) / $this->itemsPerPage),
            'currentPage' => $this->currentPage,
            'itemsPerPage' => $this->itemsPerPage,
            'totalPagesUnapproved' =>ceil(count($this->comments) / $this->itemsPerPage),
        ]);
    }
}

