@extends('layouts.app')

@section('title', '创建话题')

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
                <option value="" hidden disabled selected>请选择分类</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
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
