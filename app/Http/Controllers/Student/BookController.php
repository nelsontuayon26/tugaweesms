<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display the student's borrowed books (SF3 data)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(404, 'Student record not found');
        }

        // Get active school year
        $activeSchoolYear = SchoolYear::getActive();
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::latest('start_date')->first();
        }

        // Build query for student's books
        $query = Book::with('inventory')
            ->where('student_id', $student->id);

        if ($activeSchoolYear) {
            $query->where('school_year_id', $activeSchoolYear->id);
        }

        $allBooks = $query->orderBy('date_issued', 'desc')->get();

        // Categorize books
        $borrowedBooks = $allBooks->filter(function ($book) {
            return is_null($book->date_returned) && $book->status !== 'lost';
        });

        $returnedBooks = $allBooks->filter(function ($book) {
            return !is_null($book->date_returned) && $book->status !== 'damaged';
        });

        $damagedBooks = $allBooks->filter(function ($book) {
            return $book->status === 'damaged';
        });

        $lostBooks = $allBooks->filter(function ($book) {
            return $book->status === 'lost';
        });

        return view('student.books.index', compact(
            'student',
            'activeSchoolYear',
            'allBooks',
            'borrowedBooks',
            'returnedBooks',
            'damagedBooks',
            'lostBooks'
        ));
    }
}
