use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // ... existing code ...

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'bulk_action' => 'required|in:add_guru_piket,remove_guru_piket'
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $guruPiketRole = Role::where('name', 'Guru Piket')->first();

        if (!$guruPiketRole) {
            return redirect()->back()
                ->with('error', 'Role Guru Piket tidak ditemukan');
        }

        $count = 0;
        foreach ($users as $user) {
            if ($request->bulk_action === 'add_guru_piket' && !$user->hasRole('Guru Piket')) {
                $user->assignRole($guruPiketRole);
                $count++;
            } elseif ($request->bulk_action === 'remove_guru_piket' && $user->hasRole('Guru Piket')) {
                $user->removeRole($guruPiketRole);
                $count++;
            }
        }

        $message = $request->bulk_action === 'add_guru_piket' 
            ? 'Role Guru Piket berhasil ditambahkan ke ' 
            : 'Role Guru Piket berhasil dihapus dari ';

        return redirect()->back()
            ->with('success', $message . $count . ' pengguna');
    }
} 