<?php

use App\Models\Video;

it('converts YouTube URLs to embeddable format', function () {
    $video = new Video();

    $standardUrl = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    $embedUrl = 'https://www.youtube.com/embed/dQw4w9WgXcQ';

    expect($video->convertToEmbedUrl($standardUrl))->toBe($embedUrl);
});

it('returns non-YouTube URLs unchanged', function () {
    $video = new Video();

    $nonYouTubeUrl = 'https://example.com/video.mp4';

    expect($video->convertToEmbedUrl($nonYouTubeUrl))->toBe($nonYouTubeUrl);
});

