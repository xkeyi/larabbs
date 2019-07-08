@extends('layouts.app')

@section('title', '创建话题')

@section('styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('content')
  <div class="container">
    <div class="col-md-10 offset-md-1">
      <div class="card">
        <div class="card-body">

          <h2>
            <i class="far fa-edit"></i>
              @if ($topic->id)
                编辑话题
              @else
                新建话题
              @endif
          </h2>

          <hr>

          @if ($topic->id)
            <form method="POST" action="{{ route('topics.update', $topic->id) }}" accept-charset="UTF-8">
              @csrf
              @method('PUT')
          @else
            <form method="POST" action="{{ route('topics.store') }}" accept-charset="UTF-8">
              @csrf
          @endif

            @include('shared._error')

            <div class="form-group">
              <input type="text" name="title" class="form-control" value="{{ old('title', $topic->title) }}" placeholder="请填写标题" required />
            </div>

            <div class="form-group">
              <select class="form-control" name="category_id" required>
                <option value="" hidden disabled {{ $topic->id ? '' : 'selected' }}>请选择分类</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ $topic->category_id == $category->id ? 'selected' : ''}}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <textarea class="form-control" name="body" id="editor" rows="6" placeholder="请填写至少三个字符的内容。" required>{{ old('body', $topic->body) }}</textarea>
            </div>

            <div class="well well-sm">
              <button type="submit" class="btn btn-primary"><i class="far fa-save mr-2" aria-hidden="true"></i> 保存</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      var editor = new Simditor({
        textarea: $('#editor'),
        upload: {
          url: '{{ route("topics.upload_image") }}', // 处理上传图片的 URL
          params: {
            _token: '{{ csrf_token() }}' // 表单提交的参数，Laravel 的 POST 请求必须带防止 CSRF 跨站请求伪造的 _token 参数
          },
          fileKey: 'upload_file', // 是服务器端获取图片的键值
          connectionCount: 3, // 最多只能同时上传 3 张图片
          leaveConfirm: '文件上传中，关闭此页面将取消上传。' // 上传过程中，用户关闭页面时的提醒。
        },
        pasteImage: true // 设定是否支持图片黏贴上传
      });
    });
  </script>
@stop
