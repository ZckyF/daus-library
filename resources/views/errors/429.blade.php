{{-- @extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests')) --}}

@extends('layouts.error')

@section('title', __('To Many Requests'))  
@section('code', '429')
@section('title message', __('Too Many Requests'))
@section('message', __('Too many requests have been made to the server. Please try again later.'))