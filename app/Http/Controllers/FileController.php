<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\File as SysFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /********************************************
     * FILES
     ********************************************/
    public function uploadMethod(Request $request, $path = '/')
    {
        $path = $this->sanitizePath($path);

        $validated = $request->validate([
            'file' => 'required|file',
            'file_name' => 'nullable|string',
        ]);

        if (!$validated['file_name']) {
            $validated['file_name'] = $validated['file']->getClientOriginalName();
        }

        $filePath = sprintf('%s/%s', $path, $validated['file_name']);
        if(Storage::disk('shloud2')->exists($filePath)) {
            return redirect()->back()->withErrors(['overwrite_file' => 'File already exists',]);
        }

        $path = Storage::disk('shloud2')->putFileAs('', $validated['file'], $filePath);

        return redirect()->route('files.list', dirname($path))->with(['flash' => 'File uploaded successfully.']);
    }

    public function downloadMethod($path = '/')
    {
        $path = $this->sanitizePath($path);

        if ($path === '/') {
            return redirect()->back()->withErrors(['path' => 'file path is empty']);
        }

        if (Storage::disk('shloud2')->exists($path)) {
            $filePath = Storage::disk('shloud2')->path($path);
            return response()->download($filePath, basename($filePath));
        } else {
            return redirect()->back()->withErrors(['file' => 'File ' . $path . ' not found']);
        }
    }

    public function deleteMethod($path = '/')
    {
        $path = $this->sanitizePath($path);

        if (Storage::disk('shloud2')->exists($path)) {
            if (Storage::disk('shloud2')->delete($path)) {
                return redirect()->back()->with(['flash' => 'File deleted successfully']);
            }
            return redirect()->back()->withErrors(['file' => 'Failed to deleted file.']);
        }

        return redirect()->back()->withErrors(['file' => 'File doesn\'t exist.']);
    }

    public function renameMethod(Request $request, $path = '/')
    {
        $path = $this->sanitizePath($path);
        if ($path === '/') {
            return reidrect()->back()->withErrors(['no_path' => 'Must specify a path to rename']);
        }

        $validateData = $request->validate([
            'new_name' => 'required|string|max:255',
        ]);

        $newName = $validateData['new_name'];
        $newPath = sprintf("%s/%s", dirname($path), $newName);
        if (Storage::disk('shloud2')->exists($newName)) {
            return redirect()->back()->withErrors(['new_name_not_valid' => 'File with the same name already exists']);
        }
        if (!Storage::disk('shloud2')->exists($path)) {
            return redirect()->back()->withErrors(['path_not_valid' => $path . 'No such file or directory']);
        }
        if (!Storage::disk('shloud2')->exists(dirname($path))) {
            return redirect()->back()->withErrors(['new_name_not_valid' => 'No such file or directory']);
        }

        if(Storage::disk('shloud2')->move($path, $newPath))
        {
            return redirect()->route('files.list', dirname($path))->with(['flash' => 'File renamed successfully to ' . $newPath]);
        }

        return redirect()->back()->withErrors(['internal' => 'Failed to rename the file']);
    }
    
    public function upload($path = '/')
    {
        $path = $this->sanitizePath($path);

        return view('pages.files.upload', [
            'fieldErrors' => [],
            'isAuthenticated' => Auth::check(),
            'currentDir' => $path,
        ]);
    }

    public function rename($path = '/')
    {
        $path = $this->sanitizePath($path);

        return view('pages.files.rename', [
            'fieldErrors' => [],
            'isAuthenticated' => Auth::check(),
            'currentDir' => $path,
        ]);
    }

    public function list($path = '/')
    {
        $path = $this->sanitizePath($path);

        $sysFiles = Storage::disk('shloud2')->files($path);
        $sysDirs = Storage::disk('shloud2')->directories($path);

        $dirContents = [];
        foreach ($sysDirs as $dir) {
            $dirPath = Storage::disk('shloud2')->path($dir);
            $dirContents[] = [
                'name' => basename($dir),
                'path' => $dir,
                'created_at' => date('Y-m-d H:i:s', stat($dirPath)['ctime']),
                'type' => "folder",
            ];
        }

        foreach ($sysFiles as $file) {
            $sizeBytes = Storage::disk('shloud2')->size($file);
            $size = null;
            if ($sizeBytes >= 1024 * 1024 * 1024 * 1024) {
                $size = sprintf("%d Tib", $sizeBytes / (1024 * 1024 * 1024 * 1024));
            } else if ($sizeBytes >= 1024 * 1024 * 1024) {
                $size = sprintf("%d Gib", $sizeBytes / (1024 * 1024 * 1024));
            } elseif ($sizeBytes >= 1024 * 1024) {
                $size = sprintf("%d Mib", $sizeBytes / (1024 * 1024));
            } elseif ($sizeBytes >= 1024) {
                $size = sprintf("%d Kib", $sizeBytes / (1024));
            } else {
                $size = sprintf("%d b", $sizeBytes);
            }

            $filePath = Storage::disk('shloud2')->path($file);
            $dirContents[] = [
                'name' => basename($file),
                'path' => $file,
                'created_at' => date('Y-m-d H:i:s', stat($filePath)['ctime']),
                'type' => "file",
                'size' => $size,
            ];
        }

        return view('pages.files.list', [
            'dirContents' => $dirContents,
            'isAuthenticated' => Auth::check(),
            'currentDir' => $path,
        ]);
    }

    /********************************************
     * 
     * DIRECTORIES
     * 
     ********************************************/
    public function mkdirMethod(Request $request, $path)
    {
        $path = $this->sanitizePath($path);

        $validated = $request->validate([
            'dir_name' => 'required|string|max:255',
        ]);

        $newDirPath = sprintf('%s/%s', $path, $validated['dir_name']);

        if (!Storage::disk('shloud2')->exists($newDirPath)) {
            Storage::disk('shloud2')->makeDirectory($newDirPath);
                return redirect()->route('files.list', $path)->with(['flash' => sprintf('Directory \'%s\' created successfully.', $newDirPath)]);
        }

        return redirect()->back()->withErrors([
            'already_exists' => sprintf('Directory \'%s\'already exists', $newDirPath),
        ]);
    }

    public function rmdirMethod($path = '/')
    {
        $path = $this->sanitizePath($path);

        if(Storage::disk('shloud2')->exists($path)) {
            if($path === '/') {
                return redirect()->back()->withErrors(['root_dir' => 'Cannot remove root directory']);
            }

            Storage::disk('shloud2')->deleteDirectory($path);
            return redirect()->back()->with(['flash' => 'Directory successfully removed']);
        }
        return redirect()->back()->withErrors(['dir_doesnt_exist' => 'Directory doesn\'t exist']);
    }

    public function mkdir($path = '/')
    {
        $path = $this->sanitizePath($path);

        return view('pages.files.mkdir', [
            'currentDir' => $path,
        ]);
    }

    /********************************************
     * HELPERS
     ********************************************/

    private function sanitizePath($path)
    {
        $path = str_replace(array('..', '../', '..\\'), '', $path); // remove ../ ./ ..\
        $path = str_replace('\\', '/', $path);                      // converts \ to /
        //$path = trim($path, '/');                                   // remove trailing slashes
        $path = preg_replace('/\/+/', '/', $path);                  // remove repeated slashes

        return $path;
    }
}