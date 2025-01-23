<table class="table">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="select-all" class="form-check-input">
            </th>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>
                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->implode('name', ', ') }}</td>
            <td>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="bulk-actions mt-3">
    <form action="{{ route('admin.users.bulk-update') }}" method="POST" id="bulk-form">
        @csrf
        <select name="bulk_action" class="form-select d-inline w-auto">
            <option value="">Bulk Actions</option>
            <option value="add_guru_piket">Add Role: Guru Piket</option>
            <option value="remove_guru_piket">Remove Role: Guru Piket</option>
        </select>
        <button type="submit" class="btn btn-primary">Apply</button>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('bulk-form').addEventListener('submit', function(e) {
        const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
        if (selectedUsers.length === 0) {
            e.preventDefault();
            alert('Please select at least one user');
            return;
        }
        
        selectedUsers.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = checkbox.value;
            this.appendChild(input);
        });
    });
</script>
@endpush 