@extends('layouts.app')
@section('content')
    <form action="{{ url('cats/store') }}" method="post" class="form-control">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="parent_id">Родитель</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="0">---- Нет родителя ----</option>
                        @foreach($cats as $cat)
                            @if($cat->isRoot())
                                <option @if(Session::get('rem_cat_id') == $cat->id) selected @endif value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @if($cat->hasChildren())
                                    @foreach($cat->children as $child)
                                        <option @if(Session::get('rem_cat_id') == $child->id) selected @endif value="{{ $child->id }}">&nbsp;----{{ $child->title }}</option>
                                        @if($child->hasChildren())
                                            @foreach($child->children as $grandson)
                                                <option @if(Session::get('rem_cat_id') == $grandson->id) selected @endif value="{{ $grandson->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----{{ $grandson->title }}</option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" name="rem" type="checkbox" value="1" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                            Запомнить выбор
                        </label>
                    </div>
                </div>
            </div>
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
