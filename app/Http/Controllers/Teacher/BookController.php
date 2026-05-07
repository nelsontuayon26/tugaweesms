<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;
use App\Models\Student;
use App\Models\Book;
use App\Models\BookInventory;
use App\Models\SchoolYear;
use Carbon\Carbon;
use App\Models\GradeLevel;

class BookController extends Controller
{
    /**
     * Get logged-in teacher sections
     */
    private function getTeacherSections()
    {
        $teacher = Auth::user()->teacher;

        return Section::with(['students.user', 'gradeLevel', 'teacher.user'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', 1)
            ->get();
    }

    /**
     * Get active school year
     */
    private function getActiveSchoolYear()
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::latest('start_date')->first();
        }
        
        return $activeSchoolYear;
    }



   /**
 * Show book issue form
 */
public function issue(Section $section)
{
    // Authorization check
    if ($section->teacher_id !== Auth::user()->teacher->id) {
        abort(403);
    }

    $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
    if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
        abort(403, 'This section is not in the active school year.');
    }

    $activeSchoolYear = $this->getActiveSchoolYear();

    // Get enrolled students - sorted by gender (male first) then alphabetically by last name
    $students = Student::with('user')
        ->whereHas('enrollments', function($q) use ($section, $activeSchoolYear) {
            $q->where('section_id', $section->id)
              ->where('school_year_id', $activeSchoolYear->id)
              ->where('status', 'enrolled');
        })
        ->whereNotIn('status', ['completed', 'inactive'])
        ->get()
        ->sortBy(function ($student) {
            $gender = strtoupper($student->gender ?? '');
            $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
            return [
                $genderOrder, 
                strtolower($student->user->last_name ?? ''), 
                strtolower($student->user->first_name ?? '')
            ];
        });

    // Get available books for this grade level - FIXED: use grade_level_id instead of name
    $bookInventories = collect();
    if ($section->gradeLevel) {
        $bookInventories = BookInventory::where(function($q) use ($section) {
                $q->where('grade_level', $section->gradeLevel->name)
                  ->orWhere('grade_level', 'All')
                  ->orWhere('grade_level_id', $section->grade_level_id); // Added this line
            })
            ->where('available_copies', '>', 0)
            ->orderBy('subject_area')
            ->orderBy('title')
            ->get();
    }

    // Get currently issued books for these students (for filtering)
    $studentBookCodes = Book::whereIn('student_id', $students->pluck('id'))
        ->where('school_year_id', $activeSchoolYear->id)
        ->whereNull('date_returned')
        ->where('status', '!=', 'lost')
        ->get()
        ->groupBy('student_id')
        ->map(fn($books) => $books->pluck('book_code')->toArray())
        ->toArray();

    // Get max copy numbers per book inventory for preview
    $maxCopyNumbers = Book::whereIn('book_inventory_id', $bookInventories->pluck('id'))
        ->where('school_year_id', $activeSchoolYear->id)
        ->selectRaw('book_inventory_id, MAX(copy_number) as max_copy')
        ->groupBy('book_inventory_id')
        ->pluck('max_copy', 'book_inventory_id')
        ->toArray();

    // Format students for JavaScript with gender grouping info
    $studentsFormatted = $students->map(function ($student, $index) use ($students) {
        $gender = strtoupper($student->gender ?? '');
        $isMale = ($gender == 'MALE' || $gender == 'M');
        
        // Find male count for separator logic
        $maleCount = $students->filter(function($s) {
            $g = strtoupper($s->gender ?? '');
            return $g == 'MALE' || $g == 'M';
        })->count();
        
        return [
            'id' => $student->id,
            'full_name' => ($student->user->last_name ?? '') . ', ' . ($student->user->first_name ?? '') . ' ' . ($student->user->middle_name ?? ''),
            'gender' => $isMale ? 'M' : 'F',
            'lrn' => $student->lrn ?? null,
            'is_first_female' => (!$isMale && $index === $maleCount),
            'is_first_male' => ($isMale && $index === 0),
        ];
    })->values();

    return view('teacher.books.issue', compact(
        'section',
        'studentsFormatted',
        'studentBookCodes',
        'maxCopyNumbers',
        'bookInventories',
        'activeSchoolYear'
    ));
}

/**
 * Store book issue (supports multiple students)
 */
public function storeIssue(Request $request)
{
    $request->validate([
        'section_id' => 'required|exists:sections,id',
        'student_ids' => 'required|array|min:1',
        'student_ids.*' => 'required|exists:students,id',
        'book_inventory_id' => 'required|exists:book_inventories,id',
        'date_issued' => 'required|date',
        'condition' => 'required|in:new,good,used,damaged',
        'remarks' => 'nullable|string|max:255',
    ], [
        'book_inventory_id.required' => 'Please select a book from the inventory.',
        'student_ids.required' => 'Please select at least one student.',
        'student_ids.min' => 'Please select at least one student.',
    ]);

    $section = Section::findOrFail($request->section_id);

    // Authorization check
    if ($section->teacher_id !== Auth::user()->teacher->id) {
        abort(403);
    }

    $activeSchoolYear = $this->getActiveSchoolYear();

    // Get book inventory
    $inventory = BookInventory::findOrFail($request->book_inventory_id);

    $studentIds = $request->student_ids;
    $count = count($studentIds);

    // Check if enough copies are available
    if ($inventory->available_copies < $count) {
        return back()->with('error', "Not enough copies available. Only {$inventory->available_copies} copy/copies left for this book.");
    }

    // Check for any students who already have this book issued
    $existingBooks = Book::whereIn('student_id', $studentIds)
        ->where('book_code', $inventory->book_code)
        ->whereNull('date_returned')
        ->where('status', '!=', 'lost')
        ->pluck('student_id')
        ->toArray();

    if (!empty($existingBooks)) {
        $alreadyIssuedCount = count($existingBooks);
        return back()->with('error', "{$alreadyIssuedCount} selected student(s) already have this book issued and not yet returned.");
    }

    // Get the next available copy number for this book inventory
    $maxCopyNumber = Book::where('book_inventory_id', $inventory->id)
        ->where('school_year_id', $activeSchoolYear->id)
        ->max('copy_number') ?? 0;

    // Issue book to all selected students
    foreach ($studentIds as $index => $studentId) {
        $maxCopyNumber++;
        Book::create([
            'student_id' => $studentId,
            'title' => $inventory->title,
            'subject_area' => $inventory->subject_area,
            'book_code' => $inventory->book_code,
            'reference_code' => $inventory->isbn,
            'date_issued' => $request->date_issued,
            'date_returned' => null,
            'status' => 'issued',
            'condition' => $request->condition,
            'damage_details' => null,
            'loss_code' => null,
            'action_taken' => null,
            'remarks' => $request->remarks,
            'school_year_id' => $activeSchoolYear->id,
            'book_inventory_id' => $inventory->id,
            'copy_number' => $maxCopyNumber,
        ]);
    }

    // Update inventory counts
    $inventory->decrement('available_copies', $count);
    $inventory->increment('issued_copies', $count);

    $studentWord = $count === 1 ? 'student' : 'students';
    return redirect()->route('teacher.sf3', ['section_id' => $section->id])
        ->with('success', "Book issued successfully to {$count} {$studentWord}.");
}



    /**
     * Show book return form
     */
    public function return(Section $section)
    {
        // Authorization check
        if ($section->teacher_id !== Auth::user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $activeSchoolYear = $this->getActiveSchoolYear();

        // Get students with issued books
        $students = Student::with(['user', 'books' => function($q) {
                $q->whereNull('date_returned')
                  ->where('status', '!=', 'lost');
            }])
            ->whereHas('enrollments', function($q) use ($section, $activeSchoolYear) {
                $q->where('section_id', $section->id)
                  ->where('school_year_id', $activeSchoolYear->id)
                  ->where('status', 'enrolled');
            })
            ->whereNotIn('status', ['completed', 'inactive'])
            ->whereHas('books', function($q) {
                $q->whereNull('date_returned')
                  ->where('status', '!=', 'lost');
            })
            ->get()
            ->sortBy(function ($student) {
                $gender = strtoupper($student->gender ?? '');
                $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
                return [$genderOrder, $student->user->last_name ?? '', $student->user->first_name ?? ''];
            });

        return view('teacher.books.return', compact(
            'section',
            'students',
            'activeSchoolYear'
        ));
    }

    /**
     * Store book return
     */
    public function storeReturn(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'book_id' => 'required|exists:books,id',
            'date_returned' => 'required|date',
            'condition' => 'required|in:new,used,damaged',
            'damage_details' => 'nullable|string|max:255|required_if:condition,damaged',
            'remarks' => 'nullable|string|max:255',
        ]);

        $section = Section::findOrFail($request->section_id);

        // Authorization check
        if ($section->teacher_id !== Auth::user()->teacher->id) {
            abort(403);
        }

        $book = Book::findOrFail($request->book_id);

        // Check if book is already returned
        if ($book->date_returned) {
            return back()->with('error', 'This book has already been returned.');
        }

        // Update book record
        $book->update([
            'date_returned' => $request->date_returned,
            'condition' => $request->condition,
            'damage_details' => $request->damage_details,
            'status' => $request->condition == 'damaged' ? 'damaged' : 'returned',
            'remarks' => $request->remarks,
        ]);

        // Update inventory
        $inventory = BookInventory::where('book_code', $book->book_code)->first();
        if ($inventory) {
            $inventory->increment('available_copies');
            $inventory->decrement('issued_copies');

            if ($request->condition == 'damaged') {
                $inventory->increment('damaged_copies');
            }
        }

        return redirect()->route('teacher.sf3', ['section_id' => $section->id])
            ->with('success', 'Book returned successfully.');
    }

    /**
     * Mark book as lost
     */
    public function markAsLost(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'book_id' => 'required|exists:books,id',
            'loss_code' => 'required|in:FM,TDO,NEG',
            'action_taken' => 'required|in:LLTR,TLTR,PTLTR,OR',
            'remarks' => 'nullable|string|max:255',
        ]);

        $section = Section::findOrFail($request->section_id);

        // Authorization check
        if ($section->teacher_id !== Auth::user()->teacher->id) {
            abort(403);
        }

        $book = Book::findOrFail($request->book_id);

        // Update book record
        $book->update([
            'status' => 'lost',
            'loss_code' => $request->loss_code,
            'action_taken' => $request->action_taken,
            'remarks' => $request->remarks,
        ]);

        // Update inventory
        $inventory = BookInventory::where('book_code', $book->book_code)->first();
        if ($inventory) {
            $inventory->decrement('issued_copies');
            $inventory->increment('lost_copies');
        }

        return redirect()->route('teacher.sf3', ['section_id' => $section->id])
            ->with('success', 'Book marked as lost.');
    }

  /**
 * Display book inventory for teacher's sections
 */
public function inventory(Request $request)
{
    $teacher = Auth::user()->teacher;
    
    // Get teacher's sections for current school year
    $activeSchoolYear = $this->getActiveSchoolYear();
    
    $sections = collect();
    if ($teacher && $activeSchoolYear) {
        $sections = Section::where('teacher_id', $teacher->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->with(['gradeLevel'])
            ->get();
    }
    
    // Get selected section or first one
    $selectedSectionId = $request->section_id ?? $sections->first()?->id;
    $selectedSection = $sections->firstWhere('id', $selectedSectionId);
    
    // Get book inventories for selected section's grade level
    $bookInventories = collect();
    $totalStats = [
        'total_titles' => 0,
        'total_copies' => 0,
        'available' => 0,
        'issued' => 0,
        'damaged' => 0,
        'lost' => 0,
    ];
    
    if ($selectedSection && $selectedSection->gradeLevel && $activeSchoolYear) {
        $bookInventories = BookInventory::where(function($q) use ($selectedSection) {
                $q->where('grade_level', $selectedSection->gradeLevel->name)
                  ->orWhere('grade_level', 'All')
                  ->orWhere('grade_level_id', $selectedSection->grade_level_id);
            })
            ->withCount(['books as issued_count' => function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id)
                  ->whereNull('date_returned');
            }])
            ->orderBy('subject_area')
            ->orderBy('title')
            ->paginate(10);
        
        $totalStats = [
            'total_titles' => $bookInventories->total(),
            'total_copies' => $bookInventories->sum('total_copies'),
            'available' => $bookInventories->sum('available_copies'),
            'issued' => $bookInventories->sum('issued_copies'),
            'damaged' => $bookInventories->sum('damaged_copies'),
            'lost' => $bookInventories->sum('lost_copies'),
        ];
    }
    
    return view('teacher.books.inventory', compact(
        'sections',
        'selectedSection',
        'bookInventories',
        'totalStats',
        'activeSchoolYear'
    ));
}

/**
 * Show form to create new book inventory
 */
public function createInventory()
{
    $teacher = Auth::user()->teacher;
    $activeSchoolYear = $this->getActiveSchoolYear();
    
    // Get teacher's grade levels from their sections
    $gradeLevels = GradeLevel::whereIn('id', function($q) use ($teacher, $activeSchoolYear) {
            $q->select('grade_level_id')
              ->from('sections')
              ->where('teacher_id', $teacher->id)
              ->where('school_year_id', $activeSchoolYear->id);
        })
        ->orWhere('id', 0) // "All" option
        ->orderBy('name')
        ->get();
    
    $subjectAreas = [
        'Mathematics', 'Science', 'English', 'Filipino', 
        'Araling Panlipunan', 'MAPEH', 'ESP', 'TLE', 'EPP'
    ];
    
    return view('teacher.books.create-inventory', compact(
        'gradeLevels',
        'subjectAreas',
        'activeSchoolYear'
    ));
}

/**
 * Store new book inventory
 */
public function storeInventory(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'subject_area' => 'required|string|max:100',
        'grade_level' => 'required|string|max:50',
        'book_code' => 'required|string|max:50|unique:book_inventories',
        'isbn' => 'nullable|string|max:50',
        'publisher' => 'nullable|string|max:100',
        'publication_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        'total_copies' => 'required|integer|min:1|max:1000',
        'replacement_cost' => 'nullable|numeric|min:0',
        'remarks' => 'nullable|string|max:500',
    ]);
    
    $data = $request->all();
    $data['available_copies'] = $request->total_copies;
    $data['issued_copies'] = 0;
    $data['damaged_copies'] = 0;
    $data['lost_copies'] = 0;
    
    BookInventory::create($data);
    
    return redirect()->route('teacher.books.inventory')
        ->with('success', 'Book inventory added successfully.');
}

/**
 * Show form to edit book inventory
 */
public function editInventory(BookInventory $inventory)
{
    $teacher = Auth::user()->teacher;
    $activeSchoolYear = $this->getActiveSchoolYear();
    
    $gradeLevels = GradeLevel::whereIn('id', function($q) use ($teacher, $activeSchoolYear) {
            $q->select('grade_level_id')
              ->from('sections')
              ->where('teacher_id', $teacher->id)
              ->where('school_year_id', $activeSchoolYear->id);
        })
        ->orWhere('id', 0)
        ->orderBy('name')
        ->get();
    
    $subjectAreas = [
        'Mathematics', 'Science', 'English', 'Filipino', 
        'Araling Panlipunan', 'MAPEH', 'ESP', 'TLE', 'EPP'
    ];
    
    return view('teacher.books.edit-inventory', compact(
        'inventory',
        'gradeLevels',
        'subjectAreas'
    ));
}

/**
 * Update book inventory
 */
public function updateInventory(Request $request, BookInventory $inventory)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'subject_area' => 'required|string|max:100',
        'grade_level' => 'required|string|max:50',
        'book_code' => 'required|string|max:50|unique:book_inventories,book_code,' . $inventory->id,
        'isbn' => 'nullable|string|max:50',
        'publisher' => 'nullable|string|max:100',
        'publication_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        'total_copies' => 'required|integer|min:' . ($inventory->issued_copies + $inventory->damaged_copies + $inventory->lost_copies),
        'replacement_cost' => 'nullable|numeric|min:0',
        'remarks' => 'nullable|string|max:500',
    ]);
    
    $data = $request->all();
    
    // Recalculate available copies
    $data['available_copies'] = $request->total_copies - $inventory->issued_copies - $inventory->damaged_copies - $inventory->lost_copies;
    
    $inventory->update($data);
    
    return redirect()->route('teacher.books.inventory')
        ->with('success', 'Book inventory updated successfully.');
}

/**
 * Delete book inventory
 */
public function destroyInventory(BookInventory $inventory)
{
    // Check if books are issued
    if ($inventory->issued_copies > 0) {
        return back()->with('error', 'Cannot delete inventory with issued books. Please return all books first.');
    }
    
    $inventory->delete();
    
    return redirect()->route('teacher.books.inventory')
        ->with('success', 'Book inventory deleted successfully.');
}


    /**
     * Add copies to inventory
     */
    public function addCopies(Request $request, BookInventory $bookInventory)
    {
        $request->validate([
            'additional_copies' => 'required|integer|min:1',
        ]);

        $additional = $request->additional_copies;

        $bookInventory->increment('total_copies', $additional);
        $bookInventory->increment('available_copies', $additional);

        return redirect()->route('teacher.books.inventory')
            ->with('success', $additional . ' copies added successfully.');
    }


    /**
     * Get student books (AJAX)
     */
    public function getStudentBooks(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $activeSchoolYear = $this->getActiveSchoolYear();

        $books = Book::where('student_id', $request->student_id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->whereNull('date_returned')
            ->where('status', '!=', 'lost')
            ->get();

        return response()->json($books);
    }

    /**
     * Book history report
     */
    public function history(Request $request)
    {
        $sections = $this->getTeacherSections();
        $activeSchoolYear = $this->getActiveSchoolYear();

        $selectedSection = $request->section_id 
            ? Section::find($request->section_id)
            : $sections->first();

        $books = collect();

        if ($selectedSection) {
            $studentIds = Student::whereHas('enrollments', function($q) use ($selectedSection, $activeSchoolYear) {
                    $q->where('section_id', $selectedSection->id)
                      ->where('school_year_id', $activeSchoolYear->id);
                })
                ->pluck('id');

            $books = Book::with(['student.user'])
                ->whereIn('student_id', $studentIds)
                ->where('school_year_id', $activeSchoolYear->id)
                ->orderBy('date_issued', 'desc')
                ->get();
        }

        return view('teacher.books.history', compact(
            'sections',
            'selectedSection',
            'books',
            'activeSchoolYear'
        ));
    }
}