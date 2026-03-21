<?php
//gộp từ admin_user
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm theo Role (Quyền)
        if ($request->role === 'admin') {
            $query->where('is_admin', 1);
        } elseif ($request->role === 'user') {
            $query->where('is_admin', 0);
        }

        // Tìm kiếm chung
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->orderBy('user_id', 'desc')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // Hàm thay thế cho logic action == 'toggle'
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Đảo ngược trạng thái Active <-> Banned
        $user->status = ($user->status === 'Active') ? 'Banned' : 'Active';
        $user->save();

        $message = $user->status === 'Banned' ? 'Tài khoản đã bị khóa.' : 'Tài khoản đã được mở.';
        return back()->with('success', $message);
    }
}
?>