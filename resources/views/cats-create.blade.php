@extends('layouts.category')
@section('content')
    <form action="{{ url('cats/store') }}" method="post" class="form-control">
        {{ csrf_field() }}

        <div class="form-group">
            <label for="parent_id">Родитель</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="0">---- Нет родителя ----</option>
                @foreach($cats as $cat)
                    @if($cat->isRoot())
                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                        @if($cat->hasChildren())
                            @foreach($cat->children as $child)
                                <option value="{{ $child->id }}">&nbsp;----{{ $child->title }}</option>
                                @if($child->hasChildren())
                                    @foreach($child->children as $grandson)
                                        <option value="{{ $grandson->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----{{ $grandson->title }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title">Наименование</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="submit">Сохранить</button>
        </div>
    </form>
@stop