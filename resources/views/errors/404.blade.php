{{-- @extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found')) --}}


@extends('layouts.error')

@section('title', __('Not Found'))  
@section('code', '404')
@section('title message', __('Not Found'))
@section('message', __('The resource you are looking for could not be found.'))