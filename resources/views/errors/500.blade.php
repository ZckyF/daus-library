{{-- @extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error')) --}}


@extends('layouts.error')

@section('title', __('Server Error'))  
@section('code', '429')
@section('title message', __('Server Error'))
@section('message', __('An unexpected server error has occurred. Please try again later.'))