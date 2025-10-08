@extends('layouts.admin')

@section('title', 'File Upload Lab - Admin')
@section('page-title', 'File Upload Lab')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Upload with Storage Facade</h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">File is saved inside <code>storage/app/{{ $storageDir }}</code> using Laravel's Storage API.</p>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.uploads.storage') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Choose a file</label>
                        <input id="file" type="file" name="file" class="form-control-file" required>
                        <small class="form-text text-muted">Allowed: jpg, jpeg, png, pdf, doc, docx, txt (max 2MB)</small>
                        @error('file')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Upload via Storage</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Upload without Storage (manual)</h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">File is moved into <code>public/{{ $publicDir }}</code> using PHP's native move.</p>

                @if(session('success_public'))
                    <div class="alert alert-success">{{ session('success_public') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.uploads.public') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file_public">Choose a file</label>
                        <input id="file_public" type="file" name="file_public" class="form-control-file" required>
                        <small class="form-text text-muted">Same validation rules as above.</small>
                        @error('file_public')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-info">Upload manually</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Uploaded Files Overview</h3>
    </div>
    <div class="card-body row">
        <div class="col-md-6">
            <h5 class="font-weight-bold">Storage facade uploads</h5>
            @if(empty($storageFiles))
                <p class="text-muted">No files yet.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($storageFiles as $file)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($file['is_image'])
                                    <img src="{{ $file['open_url'] }}" alt="Preview {{ $file['name'] }}" class="img-thumbnail mr-3" style="width:80px;height:80px;object-fit:cover;">
                                @else
                                    <span class="mr-3 text-secondary"><i class="fas fa-file fa-2x"></i></span>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $file['name'] }}</div>
                                    <div class="text-muted small">{{ $file['size_human'] }} • {{ $file['mime'] }}</div>
                                    <div class="text-muted small">Path: storage/app/{{ $storageDir }}</div>
                                </div>
                                <div class="ml-3">
                                    <a href="{{ $file['open_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ $file['is_image'] ? 'View' : 'Download' }}</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-md-6">
            <h5 class="font-weight-bold">Manual uploads (public)</h5>
            @if(empty($publicFiles))
                <p class="text-muted">No files yet.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($publicFiles as $file)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                @if($file['is_image'])
                                    <img src="{{ $file['open_url'] }}" alt="Preview {{ $file['name'] }}" class="img-thumbnail mr-3" style="width:80px;height:80px;object-fit:cover;">
                                @else
                                    <span class="mr-3 text-secondary"><i class="fas fa-file fa-2x"></i></span>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $file['name'] }}</div>
                                    <div class="text-muted small">{{ $file['size_human'] }} • {{ $file['mime'] }}</div>
                                    <div class="text-muted small">Path: public/{{ $publicDir }}</div>
                                </div>
                                <div class="ml-3 text-right">
                                    <a href="{{ $file['open_url'] }}" target="_blank" class="btn btn-sm btn-outline-info mb-1">{{ $file['is_image'] ? 'View' : 'Download' }}</a>
                                    <div>
                                        <a href="{{ $file['public_url'] }}" target="_blank" class="small">Direct URL</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">What to Learn from This Lab</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>1. Validation</h5>
                <p>Both endpoints call <code>$request-&gt;validate()</code> with <strong>file</strong> rules (<code>file</code>, <code>mimes</code>, <code>max</code>). Adjust this to force image-only uploads, size limits, etc.</p>

                <h5>2. Directories</h5>
                <ul>
                    <li><code>Storage::makeDirectory()</code> creates folders inside <code>storage/app</code>.</li>
                    <li><code>File::makeDirectory()</code> creates directories under <code>public/</code>.</li>
                    <li>Both methods pass <code>true</code> for recursive creation, so nested paths work.</li>
                </ul>

                <h5>3. File existence &amp; renaming</h5>
                <p>Before writing we check with <code>Storage::exists()</code> or <code>File::exists()</code>. If the name already exists we regenerate a new name using timestamp + slug + random string.</p>
            </div>
            <div class="col-md-6">
                <h5>4. Enctype matters</h5>
                <p class="mb-2">HTML forms must include <code>enctype="multipart/form-data"</code> or the uploaded binary won't reach Laravel. Without it, <code>$request-&gt;file('...')</code> returns <code>null</code>.</p>

                <h5>5. Accessing files</h5>
                <ul>
                    <li>For Storage uploads, run <code>php artisan storage:link</code> so files become accessible from <code>public/storage</code>.</li>
                    <li>For manual uploads, files are already in <code>public/</code> and can be served directly.</li>
                </ul>

                <h5>6. Bonus checks</h5>
                <p>You can inspect the stored file using <code>Storage::size()</code>, <code>Storage::lastModified()</code>, or stream downloads. Manual files use <code>File::size()</code>, etc.</p>
            </div>
        </div>
    </div>
</div>
@endsection
