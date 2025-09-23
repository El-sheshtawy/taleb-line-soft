<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolFileContoller extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = $user->user_type == 'teacher' ? $user->profile->school : $user->profile;
        
        $files = SchoolFile::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
        return view('files.index', compact('files', 'school'));
    }

    public function store(Request $request)
    {
       $data = $request->validate([
            'name' => 'nullable|string',
            'note' => 'nullable|string',
            'file' => 'required|file|max:10048',
        ]);
        
        $user = Auth::user();
        $school = $user->profile;
    
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
    
        $data['school_id'] = $school->id;
        $data['type'] = $extension;
        $data['name'] = $data['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);
        $data['path'] = $file->store('school_files', 'public');
    
        SchoolFile::create($data);
        return redirect()->route('files.index')->with('success', 'تم اضافة الملف بنجاح');
    }
    
    public function update(Request $request, SchoolFile $schoolFile)
    {
        $data = $request->validate([
            'name' => 'nullable|string',
            'note' => 'nullable|string',
            'file' => 'nullable|file|max:10048',
        ]);
        
        $user = Auth::user();
        $school = $user->profile;
        
        if($schoolFile->school_id != $school->id){
            return back()->with('error', 'لا يمكن تعديل هذا الملف');
        }
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
    
            $data['type'] = $extension;
            $data['name'] = $data['name'] ?? pathinfo($originalName, PATHINFO_FILENAME);
            $data['path'] = $file->store('school_files', 'public');
    
            if ($schoolFile->path && Storage::disk('public')->exists($schoolFile->path)) {
                Storage::disk('public')->delete($schoolFile->path);
            }
        }else{
            $data['name'] = $data['name'] ?? $schoolFile->name;
        }
    
        $schoolFile->update($data);
    
        return redirect()->route('files.index')->with('success', 'تم تحديث الملف بنجاح');
    }
    
    public function destroy(SchoolFile $schoolFile)
    {
        $user = Auth::user();
        $school = $user->profile;
        
        if($schoolFile->school_id != $school->id){
            return back()->with('error', 'لا يمكن تعديل هذا الملف');
        }
        
        if ($schoolFile->path && Storage::disk('public')->exists($schoolFile->path)) {
            Storage::disk('public')->delete($schoolFile->path);
        }
    
        $schoolFile->delete();
    
        return redirect()->route('files.index')->with('success', 'تم حذف الملف بنجاح');
    }

    public function download(SchoolFile $schoolFile)
    {
        if (Storage::disk('public')->exists($schoolFile->path)) {
            return Storage::disk('public')->download($schoolFile->path, $schoolFile->name . '.' . $schoolFile->type);
        }
    
        return redirect()->back()->with('error', 'الملف غير موجود');
    }
    
    public function viewFile(SchoolFile $schoolFile)
    {
        if (Storage::disk('public')->exists($schoolFile->path)) {
            $filePath = Storage::disk('public')->path($schoolFile->path);
            return response()->file($filePath, [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        }
    
        return redirect()->back()->with('error', 'الملف غير موجود');
    }

}
