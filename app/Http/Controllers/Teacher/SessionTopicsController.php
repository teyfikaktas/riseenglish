<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PrivateLessonSession;
use App\Models\TopicCategory;
use App\Models\Topic;
use App\Models\SessionTopic;

class SessionTopicsController extends Controller
{
    /**
     * Show the form to manage topics for a session
     */
    public function manage($sessionId)
    {
        // Get the session and verify it belongs to the authenticated teacher
        $session = PrivateLessonSession::with(['privateLesson', 'student', 'sessionTopics.topic'])
            ->where('teacher_id', Auth::id())
            ->findOrFail($sessionId);
        
        // Get all topic categories with their topics
        $categories = TopicCategory::with(['topics' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])
        ->where('is_active', true)
        ->orderBy('order')
        ->get();
        
        // Get the count of each topic for this session
        $topicCounts = SessionTopic::where('session_id', $sessionId)
            ->selectRaw('topic_id, COUNT(*) as count')
            ->groupBy('topic_id')
            ->pluck('count', 'topic_id')
            ->toArray();
            
        return view('teacher.private-lessons.topics.manage', compact('session', 'categories', 'topicCounts'));
    }
    
    /**
     * Add a topic to a session
     */
    public function addTopic(Request $request, $sessionId)
    {
        // Validate request
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Get the session and verify it belongs to the authenticated teacher
        $session = PrivateLessonSession::where('teacher_id', Auth::id())
            ->findOrFail($sessionId);
            
        // Create new session topic
        SessionTopic::create([
            'session_id' => $sessionId,
            'topic_id' => $request->topic_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->back()->with('success', 'Konu başarıyla eklendi');
    }
    
    /**
     * Remove a topic from a session
     */
    public function removeTopic(Request $request, $sessionId)
    {
        // Validate request
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
        ]);
        
        // Get the session and verify it belongs to the authenticated teacher
        $session = PrivateLessonSession::where('teacher_id', Auth::id())
            ->findOrFail($sessionId);
            
        // Find the most recent topic occurrence for this session and topic
        $sessionTopic = SessionTopic::where('session_id', $sessionId)
            ->where('topic_id', $request->topic_id)
            ->latest()
            ->first();
            
        if ($sessionTopic) {
            $sessionTopic->delete();
            return redirect()->back()->with('success', 'Konu başarıyla kaldırıldı');
        }
        
        return redirect()->back()->with('error', 'Konu bu derste bulunamadı');
    }
    
    /**
     * View topics for a session (readonly view)
     */
    public function view($sessionId)
    {
        // Get the session and verify it belongs to the authenticated teacher
        $session = PrivateLessonSession::with(['privateLesson', 'student', 'sessionTopics.topic'])
            ->where('teacher_id', Auth::id())
            ->findOrFail($sessionId);
        
        // Get all topic categories with their topics
        $categories = TopicCategory::with('topics')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        // Get all topics covered in this session
        $sessionTopics = SessionTopic::with('topic')
            ->where('session_id', $sessionId)
            ->get();
            
        // Group by topic and count occurrences
        $topicCounts = [];
        foreach ($sessionTopics as $sessionTopic) {
            $topicId = $sessionTopic->topic_id;
            if (!isset($topicCounts[$topicId])) {
                $topicCounts[$topicId] = 0;
            }
            $topicCounts[$topicId]++;
        }
        
        return view('teacher.private-lessons.topics.view', compact('session', 'categories', 'topicCounts'));
    }
}