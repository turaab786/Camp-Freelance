@extends('layouts/contentLayoutMaster')

@section('title', 'Add Article')
@section('page-style')
    <style>
        .ck-editor__editable_inline {
            height: 250px;
            overflow: scroll;
        }
    </style>
@endsection
@section('content')
    <article-edit-component></article-edit-component>
@endsection
