<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Hiển thị trang hồ sơ
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    public function update(Request $request)
{
    $user = Auth::user();

    // Cập nhật tên
    if ($request->has('full_name')) {
        $user->full_name = $request->full_name;
        $user->save();
        return redirect('/')->with('status', 'Cập nhật tên thành công!');
    }

    // Cập nhật avatar
    if ($request->hasFile('avatar_url')) {
        try {
            $file = $request->file('avatar_url');
            
            // Kiểm tra file hợp lệ
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($ext, $allowed)) {
                return response()->json(['success' => false, 'message' => 'Chỉ chấp nhận file JPG, PNG, GIF'], 400);
            }
            
            // Tạo tên file
            $filename = time() . '_' . uniqid() . '.' . $ext;
            
            // Tạo thư mục nếu chưa có
            $avatarPath = public_path('avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0777, true);
            }
            
            // Lưu file
            $file->move($avatarPath, $filename);
            
            // Xóa ảnh cũ nếu có
            if ($user->avatar_url) {
                $oldPath = public_path($user->avatar_url);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Lưu vào database
            $user->avatar_url = 'avatars/' . $filename;
            $user->save();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'avatar_url' => 'avatars/' . $filename,
                    'message' => 'Upload thành công!'
                ]);
            }
            
            return redirect('/')->with('status', 'Cập nhật ảnh thành công!');
            
        } catch (\Exception $e) {
            \Log::error('Upload avatar error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect('/')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    return redirect('/')->with('error', 'Không có dữ liệu được gửi');
}

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng']);
        }

        // Cập nhật mật khẩu mới
        $user->password_hash = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Đổi mật khẩu thành công!');
    }

    // Xóa ảnh đại diện
    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();
        
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }
        
        $user->avatar_url = null;
        $user->save();
        
        // Nếu là request AJAX, trả về JSON
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('profile.edit')->with('status', 'Đã xóa ảnh đại diện!');
    }
}