<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mojo Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .profile-header { background: linear-gradient(135deg, #e1306c 0%, #fd1d1d 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; }
        .media-card { margin-bottom: 1.5rem; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .media-img { height: 300px; object-fit: cover; border-radius: 10px; }
        .comment-form { display: none; }
        .toggle-form { cursor: pointer; color: #e1306c; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary">Instagram Dashboard</h1>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger">Logout</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Profile Section (10 points) -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="{{ $profile['profile_picture_url'] ?? 'https://via.placeholder.com/100' }}" alt="Profile Pic" class="rounded-circle img-fluid" style="width: 100px; height: 100px;">
                </div>
                <div class="col-md-10">
                    <h2>{{ $profile['name'] ?? 'N/A' }}</h2>
                    <p class="mb-1"><strong>Username:</strong> @{{ $profile['username'] ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Account Type:</strong> {{ $profile['account_type'] ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Media Count:</strong> {{ $profile['media_count'] ?? 0 }}</p>
                    @if(isset($profile['bio'])) <p class="mb-1"><strong>Bio:</strong> {{ $profile['bio'] }}</p> @endif
                    @if(isset($profile['website'])) <p><strong>Website:</strong> <a href="{{ $profile['website'] }}" target="_blank">{{ $profile['website'] }}</a></p> @endif
                </div>
            </div>
        </div>

        <!-- Feeds Section (20 points) -->
        <h3>Your Feeds</h3>
        <div class="row">
            @forelse($media as $item)
                <div class="col-md-4">
                    <div class="card media-card">
                        @if($item['media_type'] === 'VIDEO' || $item['media_type'] === 'REELS')
                            <video src="{{ $item['media_url'] }}" class="media-img" controls></video>
                        @elseif(isset($item['children']) && $item['media_type'] === 'CAROUSEL_ALBUM')
                            <img src="{{ $item['children']['data'][0]['media_url'] ?? $item['thumbnail_url'] }}" alt="Carousel" class="media-img">
                            <div class="card-body">
                                <small>Carousel - {{ count($item['children']['data']) }} items</small>
                            </div>
                        @else
                            <img src="{{ $item['media_url'] ?? $item['thumbnail_url'] }}" alt="Post" class="media-img">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($item['caption'] ?? 'No caption', 100) }}</h6>
                            <p class="card-text"><small class="text-muted">Posted: {{ date('M d, Y', strtotime($item['timestamp'])) }}</small></p>
                            <p><a href="{{ $item['permalink'] }}" target="_blank" class="btn btn-sm btn-outline-primary">View on Instagram</a></p>

                            <!-- Comment Reply Feature (20 points) -->
                            <div class="toggle-form" onclick="toggleForm('form-{{ $item['id'] }}')">Reply to this post <i class="fas fa-comment"></i></div>
                            <form id="form-{{ $item['id'] }}" class="comment-form mt-2" action="{{ route('comment.store', $item['id']) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="message" class="form-control" placeholder="Type your reply..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Post Reply</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No media found. Post something on your Instagram!</div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
