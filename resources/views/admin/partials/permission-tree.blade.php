{{-- Permission tree checkboxes --}}
@php
    $selected = $selected ?? [];
    $viaRoles = $viaRoles ?? [];
@endphp

<div class="permission-tree">
    <div class="d-flex flex-wrap gap-2 mb-3">
        <button type="button" class="btn btn-sm btn-outline-primary" id="perm-select-all">Select all</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="perm-clear-all">Clear all</button>
    </div>

    @foreach ($groups as $group => $permissions)
        @php
            $names = array_keys($permissions);
            $checkedCount = count(array_intersect($names, $selected));
            $allChecked = $checkedCount === count($names) && count($names) > 0;
            $groupId = 'perm-group-'.\Illuminate\Support\Str::slug($group);
        @endphp
        <div class="perm-group" data-group="{{ $groupId }}">
            <div class="perm-group-head">
                <label class="perm-group-toggle">
                    <input type="checkbox" class="form-check-input perm-group-check" {{ $allChecked ? 'checked' : '' }}>
                    <span class="perm-group-title">{{ $group }}</span>
                    <span class="perm-group-count">{{ $checkedCount }}/{{ count($names) }}</span>
                </label>
                <button type="button" class="btn btn-sm btn-link perm-collapse-btn" data-bs-toggle="collapse" data-bs-target="#{{ $groupId }}" aria-expanded="true">
                    <i class="bx bx-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="{{ $groupId }}">
                <div class="perm-children">
                    @foreach ($permissions as $name => $label)
                        @php
                            $isDirect = in_array($name, $selected, true);
                            $fromRole = in_array($name, $viaRoles, true);
                        @endphp
                        <label class="perm-leaf">
                            <input type="checkbox"
                                   class="form-check-input perm-leaf-check"
                                   name="permissions[]"
                                   value="{{ $name }}"
                                   {{ $isDirect ? 'checked' : '' }}>
                            <span>
                                {{ $label }}
                                <small class="text-muted d-block">{{ $name }}</small>
                            </span>
                            @if ($fromRole && ! $isDirect)
                                <span class="badge bg-label-info">via role</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tree = document.querySelector('.permission-tree');
    if (!tree) return;

    function refreshGroup(group) {
        const leaves = group.querySelectorAll('.perm-leaf-check');
        const groupCheck = group.querySelector('.perm-group-check');
        const countEl = group.querySelector('.perm-group-count');
        const checked = [...leaves].filter(el => el.checked).length;
        if (groupCheck) {
            groupCheck.checked = checked === leaves.length && leaves.length > 0;
            groupCheck.indeterminate = checked > 0 && checked < leaves.length;
        }
        if (countEl) countEl.textContent = checked + '/' + leaves.length;
    }

    tree.querySelectorAll('.perm-group').forEach(group => {
        refreshGroup(group);

        group.querySelector('.perm-group-check')?.addEventListener('change', function () {
            group.querySelectorAll('.perm-leaf-check').forEach(el => {
                el.checked = this.checked;
            });
            refreshGroup(group);
        });

        group.querySelectorAll('.perm-leaf-check').forEach(el => {
            el.addEventListener('change', () => refreshGroup(group));
        });
    });

    document.getElementById('perm-select-all')?.addEventListener('click', () => {
        tree.querySelectorAll('.perm-leaf-check').forEach(el => el.checked = true);
        tree.querySelectorAll('.perm-group').forEach(refreshGroup);
    });

    document.getElementById('perm-clear-all')?.addEventListener('click', () => {
        tree.querySelectorAll('.perm-leaf-check').forEach(el => el.checked = false);
        tree.querySelectorAll('.perm-group').forEach(refreshGroup);
    });
});
</script>
@endpush
@endonce
