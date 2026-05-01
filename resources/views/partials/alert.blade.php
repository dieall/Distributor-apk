@if(session('success'))
<div class="flex items-center gap-3 border border-success-600 bg-success-50 text-success-600 rounded-lg px-6 py-3 mb-4" role="alert">
    <iconify-icon icon="ri:checkbox-circle-line" class="text-xl flex-shrink-0"></iconify-icon>
    <p class="mb-0 text-sm flex-1">{!! session('success') !!}</p>
    <button type="button" class="ms-auto p-0 border-0 bg-transparent text-success-600" onclick="this.parentElement.remove()">
        <iconify-icon icon="ri:close-line" class="text-lg"></iconify-icon>
    </button>
</div>
@endif

@if(session('error'))
<div class="flex items-center gap-3 border border-danger-600 bg-danger-50 text-danger-600 rounded-lg px-6 py-3 mb-4" role="alert">
    <iconify-icon icon="ri:error-warning-line" class="text-xl flex-shrink-0"></iconify-icon>
    <p class="mb-0 text-sm flex-1">{!! session('error') !!}</p>
    <button type="button" class="ms-auto p-0 border-0 bg-transparent text-danger-600" onclick="this.parentElement.remove()">
        <iconify-icon icon="ri:close-line" class="text-lg"></iconify-icon>
    </button>
</div>
@endif

@if($errors->any())
<div class="flex items-start gap-3 border border-danger-600 bg-danger-50 text-danger-600 rounded-lg px-6 py-3 mb-4" role="alert">
    <iconify-icon icon="ri:error-warning-line" class="text-xl flex-shrink-0 mt-0.5"></iconify-icon>
    <div>
        <p class="font-semibold mb-1 text-sm">Terdapat kesalahan:</p>
        <ul class="mb-0 ps-3 text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
