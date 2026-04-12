<x-admin-layout title="Quản lý Tin tức">
<style>
    .toolbar { display: flex; justify-content: space-between; align-items: center; background: #1a1c26; padding: 20px; border-radius: 16px; margin-bottom: 25px; border: 1px solid #2d2f3b; }
    .news-img { width: 60px; height: 40px; border-radius: 6px; object-fit: cover; }
    .news-id { font-family: monospace; color: #00d1ff; background: rgba(0, 209, 255, 0.1); padding: 4px 8px; border-radius: 6px; font-size: 12px; }
    .btn-edit { border: 1px solid #ffd000; color: #ffd000; padding: 8px 12px; border-radius: 8px; text-decoration: none; }
    .btn-delete { border: 1px solid #ff4466; color: #ff4466; background: transparent; padding: 8px 12px; border-radius: 8px; cursor: pointer; }
</style>

<div class="card">
    <div class="card-header">
        <div>
            <h2><i class="fas fa-newspaper" style="color: #00d1ff;"></i> Quản lý Tin tức</h2>
            <p style="color: #888; font-size: 14px;">Tổng cộng <strong>{{ $news->count() }}</strong> bài viết</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Thêm tin mới
        </a>
    </div>

    <div class="toolbar">
        <form action="{{ route('admin.news.index') }}" method="GET" style="display:flex; gap:10px;">
            <div class="input-group" style="position:relative;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:15px; color:#00d1ff;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tiêu đề tin tức..." 
                       style="background:#12141d; border:1px solid #3d404d; color:#fff; padding:10px 15px 10px 35px; border-radius:10px; width:300px;">
            </div>
            <select name="sort" onchange="this.form.submit()" style="background:#12141d; border:1px solid #3d404d; color:#fff; padding:10px; border-radius:10px;">
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
            </select>
        </form>
    </div>

    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#1f222e; color:#00d1ff; text-align:left;">
                <th style="padding:15px;">ID</th>
                <th>Hình ảnh & Tiêu đề</th>
                <th>Tóm tắt</th>
                <th>Ngày đăng</th>
                <th style="text-align:right; padding-right:20px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $item)
            <tr style="border-bottom: 1px solid #2d2f3b;">
                <td style="padding:15px;"><span class="news-id">#{{ $item->news_id }}</span></td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="{{ $item->image_url ?: 'https://via.placeholder.com/60x40' }}" class="news-img">
                        <span style="font-weight:bold; color:#fff;">{{ Str::limit($item->title, 40) }}</span>
                    </div>
                </td>
                <td style="color:#aaa; font-size:13px;">{{ Str::limit($item->content, 60) }}</td>
                <td style="color:#888;">{{ date('d/m/Y', strtotime($item->post_date)) }}</td>
                <td style="text-align:right; padding-right:20px;">
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                        <a href="{{ route('admin.news.edit', $item->news_id) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.news.destroy', $item->news_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Xóa bài này?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-admin-layout>